<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Restaurant;
use App\Entity\Product;
use App\Entity\PurchaseOrder;
use App\Entity\PurchaseOrderLine;

use App\Form\PurchaseOrderType;

use Symfony\Component\Uid\Uuid;

use App\Repository\RestaurantRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Security\Core\Security;

class PageController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(RestaurantRepository $restaurantRepository, UserRepository $userRepository): Response
    {
        return $this->render('page/index.html.twig', [
            'restaurants' => $restaurantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/restaurant/{id}", name="restaurant_index")
     */
    public function restaurant(Restaurant $restaurant): Response
    {
        return $this->render('page/restaurant.html.twig', [
            'restaurant' => $restaurant,
            'products' => $restaurant->getProducts(),
        ]);
    }

    /**
     * @Route("/restaurant/{id}/purchase", name="restaurant_purchase")
     */
    public function restaurantPurchase(Request $request, EntityManagerInterface $entityManager, Restaurant $restaurant): Response
    {  
        $purchaseOrder = new PurchaseOrder();
        $form = $this->createForm(PurchaseOrderType::class, $purchaseOrder, [
            'restaurant' => $restaurant,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->security->getUser();

            $uuid = Uuid::v4();
            $purchaseOrder->setPurchaseOrderId(strval($uuid));

            $purchaseOrder->setUser($user);
            $purchaseOrder->setRestaurant($restaurant);

            $totalPrice = 0;
            foreach ($purchaseOrder->getPurchaseOrderLines() as $purchaseOrderLine) {
                $totalPrice += $purchaseOrderLine->getProduct()->getPrice() * $purchaseOrderLine->getQuantity();
            }
            $purchaseOrder->setTotalPrice($totalPrice);

            $entityManager->persist($purchaseOrder);
            $entityManager->flush();
            return $this->redirectToRoute('restaurant_index', ['id' => $restaurant->getId()]);
        }

        return $this->render('page/restaurant-purchase.html.twig', [
            'restaurant' => $restaurant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/profile/orders", name="purchase_orders")
     */
    public function purchaseOrders(): Response
    {
        $user = $this->security->getUser();

        return $this->render('page/orders.html.twig', [
            'orders' => $user->getPurchaseOrders(),
        ]);
    }

    /**
     * @Route("/profile/orders/{id}", name="purchase_order")
     */
    public function purchaseOrder(PurchaseOrder $order): Response
    {
        return $this->render('page/order.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/product/{id}", name="restaurant_product")
     */
    public function product(Product $product): Response
    {
        return $this->render('page/restaurant.html.twig', [
            'restaurant' => $restaurant,
            'product' => $product,
        ]);
    }
}
