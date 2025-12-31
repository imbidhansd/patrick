<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model{
    use Sluggable;
    use LogsActivity;

    public function sluggable(): array{
        return ['slug' => ['source' => 'title']];
    }

    protected $fillable = [
        'title', 'slug',
        'media_id',
        'video_url',
        'price', 'max_quantity',
        'service_category_type_id',
        'email_receipt', 'content', 'payment_terms',
        'status'
    ];

    protected $table = 'products';

    public $searchColumns = [
        'all' => 'All',
        'products.title' => 'Title'
    ];

    protected static $logAttributes = [
    	'title', 'slug',
        'media_id',
        'video_url',
        'price', 'max_quantity',
        'service_category_type_id',
        'email_receipt', 'content', 'payment_terms',
        'status'
    ];

    // Foreign Ref.
    public function media (){
        return $this->belongsTo('\App\Models\Media', 'media_id', 'id');
    }

    public function service_category_type (){
        return $this->belongsTo('\App\Models\ServiceCategoryType', 'service_category_type_id', 'id')->active()->withDefault(['title' => 'All']);
    }

    public function product_service_categories (){
        return $this->hasMany('\App\Models\ProductServiceCategory', 'product_id', 'id');
    }

    // Scopes
    public function scopeOrder($query){
        return $query->orderBy($this->table.'.id', 'DESC');
    }

    public function scopeActive($query){
        return $query->where($this->table.'.status', 'active');
    }

    public function getAdminList($params){
        $query = self::query();
        $all_search_fields = array_keys($this->searchColumns);
        $query = Custom::generateAdminListQuery($params, $query, $all_search_fields, $this->table);

        $record_per_page = (isset($params['record_per_page']) && $params['record_per_page'] != "" && $params['record_per_page'] > 0) ? $params['record_per_page'] : env('APP_RECORDS_PER_PAGE', 20);
        return $query->paginate($record_per_page);
    }
}
