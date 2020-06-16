<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
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

  public final function commands()
  {
    return $this->hasMany(ProductCommand::class);
  }

  /** @noinspection PhpUnused */
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
