<?php


namespace App\Repositories;


use App\Payment;
use App\User;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Log\Logger;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class PaymentRepository
 *
 * @package App\Repositories
 */
class PaymentRepository
{

  const CACHE_KEY = 'payments';

  private Logger $logger;
  private CacheRepository $cacheRepository;

  /**
   * PostRepository constructor
   *
   * @param Logger $logger
   * @param CacheRepository $cacheRepository
   */
  public final function __construct(Logger $logger, CacheRepository $cacheRepository)
  {
    $this->logger = $logger;
    $this->cacheRepository = $cacheRepository;
  }

  /**
   * Find all payments in a page
   *
   * @param int $page
   * @return LengthAwarePaginator
   */
  public final function findPaginatedPayments($page)
  {
    $this->logger->info("Retrieving payments in page {$page}.");

    return $this->cacheRepository->remember($this->getCacheKey("paginated.$page"), now()->addHour(), function () use ($page) {
      $this->logger->info("Caching payments in page {$page}.");

      return Payment::query()->orderByDesc('id')->paginate();
    });
  }

  /**
   * Find payment by it's id
   *
   * @param int $id
   * @return Payment
   */
  public final function findPaymentById($id)
  {
    $this->logger->info("Retrieving payment {$id}.");

    return $this->cacheRepository->remember($this->getCacheKey("show.$id"), now()->addHour(), function () use ($id) {
      $this->logger->info("Caching payment {$id}.");

      return Payment::findOrFail($id);
    });
  }

  /**
   * Find payment's paginated products
   *
   * @param Payment $payment
   * @param int $page
   * @return mixed
   */
  public final function findPaginatedPaymentProducts(Payment $payment, int $page)
  {
    $this->logger->info("Retrieving payment {$payment->id}'s products in page {$page}.");

    return $this->cacheRepository->remember($this->getCacheKey("paginated.$page"), now()->addHour(), function () use ($payment, $page) {
      $this->logger->info("Caching user payment {$payment->id}'s products in page {$page}.");

      return $payment->products()->withPivot('amount')->paginate();
    });
  }

  /**
   * Remove all keys from cache
   *
   * @return void
   */
  public final function flushCache()
  {
    $this->logger->info("Flushing cache for key {$this->getCacheKey('*')}.");

    $this->cacheRepository->getStore()->flush();
  }

  /**
   * Return cache key for payment repository
   *
   * @param string $key
   * @return string
   */
  public final function getCacheKey(string $key)
  {
    return self::CACHE_KEY . '.' . $key;
  }
}
