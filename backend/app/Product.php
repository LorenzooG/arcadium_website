<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * @package App
 *
 * @property int id
 * @property string name
 * @property string image
 * @property double price
 * @property string command
 * @property string description
 *
 * @method static Product create(array $array)
 * @method static Product findOrFail(int $int)
 *
 */
final class Product extends Model
{

  protected $fillable = [
    "name",
    "image",
    "price",
    "description",
    "commands"
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
   * Sets image attribute
   *
   * @param UploadedFile $image
   */
  public final function setImageAttribute(UploadedFile $image)
  {
    $imageDirectory = Str::random();

    if (isset($this->attributes["image"])) {
      $imageDirectory = $this->attributes["image"];
    }

    $image->storeAs("images", $imageDirectory);

    $this->attributes["image"] = $imageDirectory;
  }

}
