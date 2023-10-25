<?php

namespace SchemaImmo;

use SchemaImmo\Estate\Address;
use SchemaImmo\Estate\Certifications;
use SchemaImmo\Estate\Coordinates;
use SchemaImmo\Estate\Media;
use SchemaImmo\Estate\Location;
use SchemaImmo\Estate\Social;
use SchemaImmo\Estate\Texts;

class Estate implements Arrayable
{
    public string $id;
	public string $slug;
    public string $name;
    public Address $address;

	/**
	 * @var array<string, bool|array> $features
	 */
	public array $features = [];

	/**
	 * @var Building[] $buildings
	 */
	public array $buildings = [];

	public Texts $texts;
	public Media $media;
	public Location $location;
	public Certifications $certifications;
	public Social $social;
	public ?WebExpose $expose = null;

	public function __construct(
		?string $id = null,
		?string $slug = null,
		?string $name = null,
		?Address $address = null,

		/** @var array<string, bool|array> $features */
		array $features = [],

		/** @var Building[] $buildings */
		array $buildings = [],

		Texts $texts = new Texts,
		Media $media = new Media,
		Location $location = new Location,
		Certifications $certifications = new Certifications,
		Social $social = new Social,
		?WebExpose $expose = null,
	)
	{
		if ($id !== null) {
			$this->id = $id;
		}

		if ($slug !== null) {
			$this->slug = $slug;
		}

		if ($name !== null) {
			$this->name = $name;
		}

		if ($address !== null) {
			$this->address = $address;
		}

		$this->features = $features;
		$this->buildings = $buildings;

		$this->texts = $texts;
		$this->media = $media;
		$this->location = $location;
		$this->certifications = $certifications;
		$this->social = $social;
		$this->expose = $expose;
	}

	public static function fake(): self
    {
        $estate = new self;
        $estate->id = '123';
		$estate->slug = 'elsenstrasse';
        $estate->name = 'Elsenstrasse';

        $estate->address = new Address;
        $estate->address->street = 'Elsenstrasse';
        $estate->address->number = '1';
        $estate->address->postal_code = '12345';
        $estate->address->city = 'Berlin';
        $estate->address->country = 'DE';

        $estate->address->coordinates = new Coordinates;
        $estate->address->coordinates->latitude = 52.123;
        $estate->address->coordinates->longitude = 13.123;

		$estate->media = new Media;
		$estate->media->thumbnail = new Image;
		$estate->media->thumbnail->src = 'https://example.com/image.jpg';

        return $estate;
    }

    public static function from(array $data): self
    {
        $estate = new self;
        $estate->id = $data['id'];
		$estate->slug = $data['slug'];
        $estate->name = $data['name'];

        $estate->address = Address::from($data['address']);

		if (isset($data['features'])) {
			$estate->features = $data['features'];
		}

		if (isset($data['buildings'])) {
			$estate->buildings = array_map(function (array $building) {
				return Building::from($building);
			}, $data['buildings']);
		}

		$estate->texts = isset($data['texts'])
			? Texts::from($data['texts'])
			: new Texts;

		$estate->media = isset($data['media'])
			? Media::from($data['media'])
			: new Media;

		$estate->location = isset($data['location'])
			? Location::from($data['location'])
			: new Location;

		$estate->certifications = isset($data['certifications'])
			? Certifications::from($data['certifications'])
			: new Certifications;

		$estate->social = isset($data['social'])
			? Social::from($data['social'])
			: new Social;

		if (isset($data['expose'])) {
			$estate->expose = WebExpose::from($data['expose']);
		}

        return $estate;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
			'slug' => $this->slug,
            'name' => $this->name,
            'address' => $this->address->toArray(),

			'features' => $this->features,
			'buildings' => array_map(function (Building $building) {
				return $building->toArray();
			}, $this->buildings),

			'texts' => $this->texts->toArray(),
			'media' => $this->media->toArray(),
			'location' => $this->location->toArray(),
			'certifications' => $this->certifications->toArray(),
			'social' => $this->social->toArray(),

			'expose' => $this->expose?->toArray(),
        ];
    }
}
