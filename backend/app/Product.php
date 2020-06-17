<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * @package App
 *
 * @property int id
 * @property string title
 * @property string image
 * @property double price
 * @property string command
 * @property string description
 * @property Carbon created_at
 * @property Carbon updated_at
 *
 * @method static Product create(array $array)
 * @method static Product findOrFail(int $int)
 *
 */
final class Product extends Model
{

  protected $fillable = [
    'title',
    'image',
    'price',
    'description',
  ];

  protected $hidden = [
    "commands"
  ];

  /**
   * Retrieve the commands of this product
   *
   * @return HasMany
   */
  public final function commands()
  {
    return $this->hasMany(ProductCommand::class);
  }

  /**
   * Gets image attribute
   *
   * @return string
   */
  public final function getImageAttribute()
  {
    return $this->attributes['image_url'];
  }

  /**
   * Sets image attribute
   *
   * @param UploadedFile $image
   */
  public final function setImageAttribute(UploadedFile $image)
  {
    $imageDirectory = Str::random(32);

    if (isset($this->attributes['image_url'])) {
      $imageDirectory = $this->attributes['image_url'];
    }

    $image->storeAs('products.images', $imageDirectory);

    $this->attributes['image_url'] = $imageDirectory;
  }

}
