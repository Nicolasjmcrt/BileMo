<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Customer;
use App\Representation\Users;
use OpenApi\Annotations as OA;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\CustomerRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractFOSRestController
{
    private $serializer;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, SerializerInterface $serializer)
    {
        $this->userPasswordHasher = $userPasswordHasher;
        $this->serializer = $serializer;
    }

    
    
    /**
     * Return users list from a customer
     * 
     * @Route("/api/customers/{id}/users", name="users_list", methods={"GET"})
     * 
     * @QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="0",
     *     description="Offset."
     * )
     * @QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="5",
     *     description="Maximum user per page."
     * )
     * @View(serializergroups={"LIST_USER"})
     * @OA\Tag(name="User")
     * @Security(name="Bearer")
     * @OA\Parameter(
     *     name="offset",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Parameter(
     *     name="limit",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * @OA\Response(
     *     response=200,
     *     description="Return the list of the users.",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref=@Model(type=User::class, groups={"LIST_USER"}))
     *     )
     * )
     * @OA\Response(
     *     response=401,
     *     description="The JWT Token is invalid."
     * )
     * @OA\Response(
     *      response=404,
     *      description="customer not found")
     */
    public function customer_users_list(Customer $customer, ParamFetcher $paramFetcher, UserRepository $userRepository )
    {


        
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        $users = $userRepository->findByCustomer($customer->getId(), [], $limit, $offset);

        return new Users($users);
        // if (!$customer) {
        //     return $this->json("Customer not found", 403, []);
        // }

        if (!$customer) {
            throw new NotFoundHttpException("The customer was not found");
        }

      

        

        // return $this->json($userRepository->findByCustomer($customerId), 200, [], ['groups' => 'users:read']);

        return new Users($users);
    }


    /**
     * Returns the user details of a customer
     * 
     * @Route("/api/customers/{id}/users/{user_id}", name ="customer_user_details", methods={"GET"})
     * @return User
     * @View(serializergroups={"SHOW_USER"})
     * @OA\Tag(name="User")
     * @Security(name="Bearer")
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     @OA\Schema(type="integer")
     * )
     * 
     * @OA\Parameter(
     *     name="user_id",
     *     in="path",
     *     @OA\Schema(type="integer")
     * )
     * 
     * @OA\Response(
     *     response=200,
     *     description="Return user details.",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"SHOW_USER"}))
     * )
     * @OA\Response(
     *     response=401,
     *     description="The JWT Token is invalid."
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="User not found."
     * )
     */
    public function customer_user_details(Customer $customer = null, User $user = null)
    {

        return $user;
        // $user = $userRepository->findOneBy([
        //     'id' => $user_id,
        //     'customer' => $id
        // ]);

        // if (!$user) {
        //     return $this->json("User not found", 404, []);
        // }

        // return $this->json($user, 200, [], ['groups' => 'users:read']); 
    }

    /**
     * Add a new user for a customer
     * 
     * @Route("/api/customers/{id}/users", name="api_users_add", methods={"POST"})
     * 
     * @OA\Tag(name="User")
     * @View(serializergroups={"SHOW_USER"})
     * @Security(name="Bearer")
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     @OA\Schema(type="integer")
     * )
     * 
     * @OA\RequestBody(
     *      required = true,
     *      @OA\MediaType(
     *          mediaType = "application/json",
     *          @OA\Schema(
     *              @OA\Property(property = "username", type = "string"),
     *              @OA\Property(property = "last_name", type = "string"),
     *              @OA\Property(property = "first_name", type = "string"),
     *              @OA\Property(property = "email", type = "string")
     *          )
     *     )
     * )
     * @OA\Response(
     *     response=201,
     *     description="User successfuly added.",
     *     @OA\JsonContent(ref=@Model(type=User::class))
     * )
     * @OA\Response(
     *     response=401,
     *     description="The JWT Token is invalid."
     * )
     * 
     * @OA\Response(
     *     response=400,
     *     description="Invalid data."
     * )
     * 
     * @OA\Response(
     *     response=404,
     *     description="Customer not found."
     * )
     */
    public function add($id, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator, CustomerRepository $customerRepository)
    {

        $jsonData = $request->getContent();

        try {
            $user = $serializer->deserialize($jsonData, User::class, 'json');

            $password = $this->userPasswordHasher->hashPassword($user, 'password');
            $user->setPassword($password);
            $user->setCreationDate(new \DateTime());

            $customer = $customerRepository->findOneBy([
                'id' => $id
            ]);
            if (!$customer) {
                return $this->json("Customer not found", 403, []);
            }
            $user->setCustomer($customer);

            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $em->persist($user);
            $em->flush();

            return $this->json($user, 201, []);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Removes a user from a customer
     * 
     * @Route("/api/customers/{id}/users/{user_id}", name="delete_user", methods={"DELETE"})
     * 
     * @OA\Tag(name="User")
     * @Security(name="Bearer")
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     @OA\Schema(type="integer")
     * )
     * 
     * @OA\Parameter(
     *     name="user_id",
     *     in="path",
     *     @OA\Schema(type="integer")
     * )
     * 
     * @OA\Response(
     *     response=204,
     *     description="User successfuly deleted.",
     *     @OA\JsonContent(ref=@Model(type=User::class))
     * )
     * @OA\Response(
     *     response=401,
     *     description="The JWT Token is invalid."
     * )
     * 
     * * @OA\Response(
     *     response=404,
     *     description="Customer not found."
     * )
     */
    public function delete($id, $user_id, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $user = $userRepository->findOneBy([
            'id' => $user_id,
            'customer' => $id
        ]);

        if (!$id) {
            return $this->json("Customer not found", 404, []);
        }

        if ($user) {
            $em->remove($user);
            $em->flush();
        }

        return $this->json($user, 204, [], ['groups' => 'users:read']); 
    }
}
