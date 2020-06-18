<?php


namespace Tests\Mocks;


use App\Payment\Contracts\PaymentHandlerContract;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class PaymentHandlerMock implements PaymentHandlerContract
{

  private ?int $idMock;
  private ?int $preferenceIdMock;
  private ?string $linkMock;

  public function __construct(int $idMock = null, int $preferenceIdMock = null, string $linkMock = null)
  {
    $this->idMock = $idMock;
    $this->preferenceIdMock = $preferenceIdMock;
    $this->linkMock = $linkMock;
  }

  public function handleCheckout(User $user, string $userName, string $originIpAddress, array $items): Response
  {
    return response()->json([
      'id' => $this->idMock,
      'preference_id' => $this->preferenceIdMock,
      'link' => $this->linkMock
    ]);
  }

  public function handleNotification(Request $request): Response
  {
    return response()->noContent();
  }

  public function getNotificationUrl(): string
  {
    // TODO: Implement getNotificationUrl() method.
  }
}
