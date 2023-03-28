<?php

namespace App\Api\Currency;

use App\Entity\Currency\Currency;
use App\Repository\Currency\CurrencyRepository;
use CurrencyApi\CurrencyApi\CurrencyApiClient;
use CurrencyApi\CurrencyApi\CurrencyApiException;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * CurrencyAPI client.
 */
class CurrencyApiManager
{

    private CurrencyApiClient $client;

    /**
     * @param CurrencyRepository $currencyRepository Currency repository.
     * @param ObjectManager      $objectManager      Doctrine object manager.
     * @param string             $apiKey             CurrencyAPI key.
     */
    public function __construct(
        private readonly CurrencyRepository $currencyRepository,
        private readonly ObjectManager $objectManager,
        string $apiKey,
    ) {
        $this->client = new CurrencyApiClient($apiKey);
    }

    /**
     * Load currencies coefficients.
     *
     * @param ProgressBar|null $progress Console progress bar.
     *
     * @return void
     * @throws CurrencyApiException
     */
    public function loadCoefficients(ProgressBar $progress = null): void
    {
        $data = $this->client->latest(['base_currency' => 'USD']);
        $progress?->start(count($data['data']));

        foreach ($data['data'] as $datum) {
            $progress?->advance();

            $currency = $this->getCurrency($datum['code']);
            $currency->setUsdExchangeRate($datum['value']);

            $this->objectManager->persist($currency);
            $this->objectManager->flush();
        }
        $progress?->finish();
    }


    /**
     * Load currencies from CustomerAPI.
     *
     * @param ProgressBar|null $progress Console progress bar.
     *
     * @return array<Currency>
     * @throws CurrencyApiException
     */
    public function loadCurrencies(ProgressBar $progress = null): array
    {
        $data = $this->client->currencies([]);
        $currencies = [];

        $progress?->start(count($data['data']));
        foreach ($data['data'] ?? [] as $datum) {
            $progress?->advance();
            $currency = $this->processApiCurrency($datum);
            $this->objectManager->persist($currency);
            $this->objectManager->flush();

            $currencies[] = $currency;
        }
        $progress?->finish();

        return $currencies;
    }

    /**
     * Get currency with the given code from database or create it.
     *
     * @param string $code Currency code.
     *
     * @return Currency
     */
    private function getCurrency(string $code): Currency
    {
        $currency = $this->currencyRepository->findOneByCode($code)
            ?: new Currency();

        $currency->setCode($code);

        return $currency;
    }

    /**
     * Convert API currency dataset into Currency API.
     *
     * @param array $datum API currency dataset.
     *
     * @return Currency
     */
    private function processApiCurrency(array $datum): Currency
    {
        $currency = $this->getCurrency($datum['code']);

        $currency->setSymbol($datum['symbol']);
        $currency->setName($datum['name']);
        $currency->setNativeSymbol($datum['symbol_native']);
        $currency->setDecimalDigits($datum['decimal_digits']);
        $currency->setRounded($datum['rounding']);
        $currency->setCode($datum['code']);
        $currency->setPluralName($datum['name_plural']);

        return $currency;
    }

}