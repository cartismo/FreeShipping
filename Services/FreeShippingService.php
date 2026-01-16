<?php

namespace Modules\FreeShipping\Services;

use App\Models\InstalledModule;

class FreeShippingService
{
    protected ?array $settings = null;

    /**
     * Get module settings
     */
    public function getSettings(): array
    {
        if ($this->settings === null) {
            $module = InstalledModule::where('slug', 'free-shipping')->first();

            $defaultSettings = [
                'enabled' => true,
                'title' => 'Free Shipping',
                'description' => 'Free standard shipping',
                'minimum_order_amount' => null,
                'sort_order' => 0,
            ];

            $this->settings = array_replace_recursive($defaultSettings, $module?->settings ?? []);
        }

        return $this->settings;
    }

    /**
     * Check if module is enabled
     */
    public function isEnabled(): bool
    {
        return $this->getSettings()['enabled'] ?? false;
    }

    /**
     * Get shipping method title
     */
    public function getTitle(): string
    {
        return $this->getSettings()['title'] ?? 'Free Shipping';
    }

    /**
     * Get shipping method description
     */
    public function getDescription(): string
    {
        return $this->getSettings()['description'] ?? '';
    }

    /**
     * Check if free shipping is available for cart total
     */
    public function isAvailable(float $cartTotal): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        $minimumAmount = $this->getSettings()['minimum_order_amount'] ?? null;

        // If no minimum is set, free shipping is always available
        if ($minimumAmount === null) {
            return true;
        }

        return $cartTotal >= $minimumAmount;
    }

    /**
     * Get shipping method data for checkout
     */
    public function getShippingMethod(float $cartTotal): ?array
    {
        if (!$this->isAvailable($cartTotal)) {
            return null;
        }

        return [
            'id' => 'free-shipping',
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'cost' => 0,
            'formatted_cost' => 'Free',
        ];
    }

    /**
     * Get amount remaining for free shipping eligibility
     */
    public function getAmountRemaining(float $cartTotal): ?float
    {
        $minimumAmount = $this->getSettings()['minimum_order_amount'] ?? null;

        if ($minimumAmount === null || $cartTotal >= $minimumAmount) {
            return null;
        }

        return $minimumAmount - $cartTotal;
    }

    /**
     * Get minimum order amount for free shipping
     */
    public function getMinimumOrderAmount(): ?float
    {
        return $this->getSettings()['minimum_order_amount'] ?? null;
    }
}