<?php

namespace Tests\Integrations;

/**
 * @group integration
 */
final class PromotionCodeTest extends BaseTestCase
{
    public function testCreatePromotionCode()
    {
        $coupon = $this->getApiClient()->createCoupon([
            'name' => 'test coupon from sdk-php',
            'discountType' => 'amount',
            'discountAmount' => 50,
            'currency' => 'JPY'
        ])->asJson();

        $promotionCode = $this->getApiClient()->createPromotionCode([
            'code' => 'promotion code from sdk-php ' . microtime(),
            'coupon' => $coupon['id']
        ])->asJson();
        $this->assertArrayHasKey('id', $promotionCode);

        return $promotionCode['id'];
    }

    /**
     * @depends testCreatePromotionCode
     */
    public function testUpdatePromotionCode($promotionCodeId)
    {
        $updatePromotionCode = $this->getApiClient()->updatePromotionCode([
            'id' => $promotionCodeId,
            'active' => false
        ])->asJson();
        $this->assertSame(false, $updatePromotionCode['active']);
        return $updatePromotionCode['id'];
    }

    /**
     * @depends testUpdatePromotionCode
     */
    public function testGetPromotionCode($promotionCodeId)
    {
        $getPromotionCode = $this->getApiClient()->getPromotionCode(['id' => $promotionCodeId]);
        $this->assertSame(200, $getPromotionCode->getStatusCode());
        $this->assertSame(false, $getPromotionCode->asJson()['active']);
    }

    /**
     * @depends testCreatePromotionCode
     */
    public function testGetPromotionCodes()
    {
        $couponsResponse = $this->getApiClient()->getPromotionCodes()->asJson();
        $this->assertSame('promotionCode', $couponsResponse['data'][0]['object']);
    }
}
