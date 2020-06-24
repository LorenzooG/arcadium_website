<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\UploadedFile;

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
 * @property PurchasedProduct pivot
 *
 * @method static Product create(array $array)
 * @method static Product findOrFail(int $int)
 *
 */
final class Product extends Model
{
  use SoftDeletes;

  /**
   * The storage key that the system will use to save the images
   */
  public const IMAGES_STORAGE_KEY = 'products.images';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'title',
    'image',
    'price',
    'description',
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
   * Saves product image
   *
   * @param UploadedFile $image
   */
  public final function saveImage($image)
  {
    $image->storeAs(self::IMAGES_STORAGE_KEY, $this->id);
  }
}
