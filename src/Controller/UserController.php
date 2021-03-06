<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Customer;
use App\Representation\Users;
use OpenApi\Annotations as OA;
use App\Repository\UserRepository;
use App\Exception\ForbiddenException;
use App\Repository\ProductRepository;
use App\Repository\CustomerRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Exception\ResourceValidationException;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * 
     * @ParamConverter("customer", options={"id"="id"})
     * @ParamConverter("user", options={"id"="user_id"})
     * 
     * @return User
     * @View(serializergroups={"SHOW_USER"})
     * @OA\Tag(name="User")
     * @Security(name="Bearer")
     * 
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
     * 
     * @OA\Response(
     *     response=403,
     *     description="Not authorized."
     * )
     */
    public function customer_user_details(Customer $customer = null, User $user = null)
    {

        if (!$user) {
            throw new NotFoundHttpException("The user was not found");
        }

        if ($user->getCustomer()->getId() != $this->getUser()->getId()) {
            throw new ForbiddenException("Not authorized");
        }

        if ($customer->getId() != $this->getUser()->getId()) {
            throw new ForbiddenException("Not authorized");
        }

        
        return $user;
    }

    /**
     * Add a new user for a customer
     * 
     * @Route("/api/customers/{id}/users", name="api_users_add", methods={"POST"})
     * 
     * @ParamConverter("user", converter="fos_rest.request_body")
     * 
     * @OA\Tag(name="User")
     * @View
     * @Security(name="Bearer")
     * @OA\Parameter(
     *     name="id",
     *     in="path",
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
    public function add(User $user, Customer $customer = null, ConstraintViolationList $violations)
    {

        if (!$customer || $this->getUser()->getId() !== $customer->getId()) {
            throw new ForbiddenException('Forbidden');
        }

        // Ajout des variables manquantes
        $password = $this->userPasswordHasher->hashPassword($user, 'password');

        $user->setCustomer($customer);
        $user->setCreationDate(new \DateTime());
        $user->setPassword($password);

            if (count($violations) > 0) {
                throw new ResourceValidationException('invalid data');
            }

        // Enregistrement de l'utilisateur
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->View($user, 201);

    }

    /**
     * Removes a user from a customer
     * 
     * @Route("/api/customers/{id}/users/{user_id}", name="delete_user", methods={"DELETE"})
     * 
     * @ParamConverter("customer", options={"id"="id"})
     * @ParamConverter("user", options={"id"="user_id"})
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
     * 
     * @OA\Response(
     *     response=403,
     *     description="Not authorized."
     * )
     */
    public function delete(Customer $customer = null, User $user = null)
    {
        if (!$user) {
            throw new NotFoundHttpException("The user was not found");
        }

        if ($user->getCustomer()->getId() != $this->getUser()->getId()) {
            throw new ForbiddenException("Not authorized");
        }

        if ($customer->getId() != $this->getUser()->getId()) {
            throw new ForbiddenException("Not authorized");
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->json([], 204); 
    }
}
