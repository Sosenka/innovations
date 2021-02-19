<?php


namespace App\Controller;


use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CurrencyController extends AbstractController
{
    private $currencyRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->currencyRepository = $this->em->getRepository(Currency::class);
    }

    public function saveCurrency($content)
    {
        foreach ($content as $currencyItem)
        {
            if($this->checkCurrencyExisting($currencyItem))
            {
                $currency = new Currency();
                $currency->setName($currencyItem['currency'])
                    ->setCurrencyCode($currencyItem['code'])
                    ->setExchangeRate($currencyItem['mid']);

                $this->em->persist($currency);


            }
            else
            {
                $this->updateCurrency($currencyItem);
            }
            $this->em->flush();


        }

    }

    private function checkCurrencyExisting($currencyItem)
    {
        $response = $this->currencyRepository->findBy(['currencyCode' => $currencyItem['code']]);

        if($response == null)
        {
            return true;
        }
        else
        {
            return false;
        }


    }

    private function updateCurrency($currencyItem)
    {
        $currency = $this->currencyRepository->findOneBy(['currencyCode' => $currencyItem['code']]);

        if ($currency != null && $currencyItem['mid'] !== $currency->getExchangeRate())
        {
            $currency->setExchangeRate($currencyItem['mid']);
        }
        return true;
    }

}