<?php

namespace App\Entity\ValueObject;

class ShippingData
{
    protected ?string $type;

    protected ?string $name;

    protected ?string $first_name;

    protected ?string $last_name;

    protected ?string $city;

    protected ?string $address;

    protected ?string $opening;

    protected ?string $description;

    protected ?string $email;

    protected ?string $phone;

    protected ?string $providerName = null;

    protected ?string $zip;

    protected ?string $providerId = null;

    public static function parseShop($shop): self
    {
        $shippingData = new self();

        $shippingData->setType('shop');
        $shippingData->setProviderName('Álomgyár könyvesboltban');
        $shippingData->setName($shop->title ?? '');
        $shippingData->setZip($shop->zip_code ?? '');
        $shippingData->setCity($shop->city ?? '');
        $shippingData->setAddress($shop->address ?? '');
        $shippingData->setOpening(self::prepareShopOpening($shop->opening_hours ?? []));
        $shippingData->setPhone($shop->phone ?? '');
        $shippingData->setEmail($shop->email ?? '');
        $shippingData->setDescription($shop->description ?? '');

        return $shippingData;
    }

    public static function parseBox(mixed $shippingDetails)
    {
        $obj = new self;
        $obj->setProviderName($shippingDetails->provider_name);
        $obj->setName($shippingDetails->name ?? '');
        $obj->setZip($shippingDetails->zip ?? 0);
        $obj->setCity($shippingDetails->city ?? '');
        $obj->setAddress($shippingDetails->address ?? '');
        $obj->setOpening($shippingDetails->open ?? '');
        $obj->setDescription($shippingDetails->description ?? '');
        $obj->setProviderId($shippingDetails->provider_id ?? '');

        return $obj;
    }

    public static function parseAddress(mixed $shippingDetails, $shippingMethodType = '-')
    {
        $providerName = strtoupper($shippingMethodType);
        $name = $shippingDetails->first_name.' '.$shippingDetails->last_name;

        $obj = new self;
        $obj->setProviderName($providerName);
        $obj->setName($name ?? '');
        $obj->setFirstName($shippingDetails->first_name ?? '');
        $obj->setLastName($shippingDetails->last_name ?? '');
        $obj->setZip($shippingDetails->zip ?? 0);
        $obj->setCity($shippingDetails->city ?? '');
        $obj->setAddress($shippingDetails->address ?? '');
        $obj->setOpening($shippingDetails->open ?? '');
        $obj->setDescription($shippingDetails->description ?? '');
        $obj->setProviderId($shippingDetails->provider_id ?? '');

        return $obj;
    }

    private static function prepareShopOpening($opening_hours): string
    {
        $openAt = [];

        foreach ($opening_hours as $daily) {
            $openAt[] = sprintf('%s: %s', ucfirst(mb_strtolower($daily['days'])), $daily['hours']);
        }

        return implode(', ', $openAt);
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(?string $first_name): void
    {
        $this->first_name = $first_name;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): void
    {
        $this->last_name = $last_name;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getOpening(): ?string
    {
        return $this->opening;
    }

    public function setOpening(?string $opening): void
    {
        $this->opening = $opening;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getZip(): ?string
    {
        return $this->zip;
    }

    public function setZip(?string $zip): void
    {
        $this->zip = $zip;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getFullAddress(): string
    {
        return $this->zip.' '.$this->city.', '.$this->address;
    }

    public function getProviderName(): ?string
    {
        return $this->providerName;
    }

    public function setProviderName(?string $providerName): void
    {
        $this->providerName = $providerName;
    }

    public function getProviderId(): ?string
    {
        return $this->providerId;
    }

    public function setProviderId(?string $providerId): void
    {
        $this->providerId = $providerId;
    }
}
