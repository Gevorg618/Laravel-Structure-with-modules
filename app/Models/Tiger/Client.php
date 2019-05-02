<?php
    
namespace App\Models\Tiger;

use App\Models\BaseModel;

class Client extends BaseModel
{
    
    /**
     * Database Connection
     * @var string
     */
    protected $connection = 'tiger';
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'client';
    
    /**
     * We don't have timestamps
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Client fillable fields
     * @var array
     */
    protected $fillable = [
        'domain',
        'title'
    ];
    
    /**
     * Connection to client options and values
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function optionValues()
    {
        return $this->belongsToMany('App\Models\Tiger\ClientOption', 'client_option_value', 'client_id', 'option_id')->withPivot('value');
    }
    
    /**
     * Connection to option values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function values()
    {
        return $this->hasMany('App\Models\Tiger\ClientOptionValue');
    }
    
    /**
     * Add existing options to clients attributes
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function withOption()
    {
        $clients = Client::with('optionValues')
            ->get();
    
        foreach ($clients as $client) {
            foreach ($client->optionValues AS $option) {
                $client->{$option->key} = $option->pivot->value;
            }
        }
        
        return $clients;
        
    }
    
    /**
     * Store Client data
     *
     * @param $request
     * @return bool
     */
    public function store($request)
    {
        $client = Client::findOrNew($request->id);
        $client->fill($request->all());
        
        $client->save();
        
        if($request->options) {
            $option = [];
            foreach ($request->options as $key => $val) {
                $option[$key] = ['value' => $val];
            }
            $client->optionValues()->sync($option);
        }
        
        
        return true;
    }
    
    /**
     * Create option=>value pair and place them in attributes
     *
     * @return array
     */
    public function optionWithValue()
    {
        $options = [];
        foreach ($this->values AS $value) {
            $options[$value->option_id] = $value->value;
        }
        
        return $options;
    }
    
}