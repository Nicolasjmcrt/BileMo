<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class UserController extends AbstractController
{
    /**
     * @Route("/api/customers/{id}/users", name="customer_users_list", methods={"GET"})
     */
    public function customer_users_list($id, UserRepository $userRepository, CustomerRepository $customerRepository, SerializerInterface $serializer)
    {

        $customer = $customerRepository->findOneBy([
            'id' => $id
        ]);

        $customerId = $customer->getId();

        return $this->json($userRepository->findByCustomer($customerId), 200, [], ['groups' => 'users:read']);
    }


    /**
     * @Route("/api/customers/{id}/users/", name ="customer_user_details", methods={"GET"})
     */
    public function customer_user_details($id, UserRepository $userRepository, CustomerRepository $customerRepository)
    {
        $customer = $customerRepository->findOneBy([
            'id' => $id
        ]);

        $customerId = $customer->getId();

        $users = $userRepository->findByCustomer($customerId);

        $user = $userRepository->findOneBy([
            'id' => $id
        ]);

        // $userId = $user->getId();

        dump($user);
        exit();
    }

    /**
     * @Route("/api/customers/{id}/users", name="api_users_add", methods={"POST"})
     */
    public function add($id, Request $request, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {

        $jsonData = $request->getContent();

        try {
            $user = $serializer->deserialize($jsonData, User::class, 'json');



            $user->setPassword("password");
            $user->setCreationDate(new \DateTime());
            // $user->setCustomer(6);

            $errors = $validator->validate($user);

            if (count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $em->persist($user);
            $em->flush();

            return $this->json($user, 201, [], ['groups' => 'users:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }
}
