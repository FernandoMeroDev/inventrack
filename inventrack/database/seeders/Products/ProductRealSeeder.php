<?php

namespace Database\Seeders\Products;

use App\Models\Products\Product;
use App\Models\Products\SalePrice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductRealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $image_not_found = [];
        foreach($this->products as $data){
            $product = Product::create([
                'name' => $data[0],
                'min_stock' => 1,
                'image_uploaded' => true,
            ]);
            SalePrice::create([
                'units_number' => 1,
                'value' => $data[1],
                'product_id' => $product->id,
            ]);
            $image_path = "storage/app/private/product_images/" . $this->image(name: $data[0]);
            if(File::exists($image_path)){
                File::copy($image_path, "storage/app/public/$product->id");
            } else {
                $image_not_found[] = [
                    'product' => $product,
                    'path' => $image_path
                ];
            }
        }
        if(count($image_not_found) > 0){
            dump('/!\\ Images Not Found /!\\');
            dump('TOTAL: ' . count($this->products));
            dump('NOT FOUND: ' . count($image_not_found));
            dump($image_not_found[0]);
            dump('/!\\ Images Not Found /!\\');
        }
    }

    /**
     * Constructs the image file's name.
     * Example: 'RON SAN MIGUEL SILVER' => 'ron_san_miguel_silver.jpg'
     */
    private function image(string $name): string
    {
        $replaced = str_replace(' ', '_', $name);
        return mb_strtolower($replaced) . '.jpg';
    }

    //
    // williaMs

    private array $products = [
        // COCTELERIA 1
        ['SILVER RON SAN MIGUEL', 9],
        ['GOLD RON SAN MIGUEL', 10],
        ['RON PONPON', 4.25],
        ['RON CABALLO VIEJO', 12],
        ['RON 100 FUEGOS', 13],
        ['RON VIEJO DE CALDAS', 14],
        ['RON CUBANERO ORO', 7],
        ['RON CASTILLO BLANCO', 9],
        ['RON ESTELAR', 8.5],
        ['SILVER RON CARTAVIO', 9.5],
        ['BLACK RON CARTAVIO', 8.5],
        ['DAIQUIRI COCKTAIL SAN MIGUEL', 7],
        ['MOJITO COCKTAIL SAN MIGUEL', 10],
        ['MARGARITA TEQUILA EL CHARRO', 10],
        ['CARTA BLANCA RON BACARDI', 17.5],
        ['CARTA ORO RON BACARDI', 17.5],
        // COCTELERIA 2
        ['VODKA SKYY', 14],
        ['MAZERATTO', 7.5],
        ['SUMMER LOVE', 7.5],
        ['PIÑA COLADA BAMBUCA', 7],
        ['CREMA SABOR A WHISKY COLDS', 9],
        ['PIÑA COLADA COLDS', 9],
        ['LES DEUX', 20],
        ['MARACUVIBES CUBATA', 3],
        ['GUARANA CUBATA', 3],
        ['LIMA LIMON CUBATA', 3],
        ['AZUL RUSSOK', 5],
        ['ROSA RUSSOK', 5],
        ['NARANJILLA RUSSOK', 4.25],
        ['SANDIA RUSSOK', 4.25],
        ['SWITCH', 3],
        ['ROJO VODKA RUSO NEGRO', 4.5],
        ['AZUL VODKA RUSO NEGRO', 4.5],
        ['ROJO SIBERIAN', 7],
        ['VERDE SIBERIAN', 7],
        ['AZUL SIBERIAN', 7.5],
        ['RUSSKAYA', 10],
        // TEQUILAS 1
        ['TEQUILA CARTAGO', 7],
        ['ORO TEQUILA AZTECA', 16],
        ['BLANCO TEQUILA AZTECA', 16],
        ['GUITARRA TEQUILA AZTECA', 18],
        ['PINK TEQUILA KATRINA', 10.5],
        ['CANNABIS TEQUILA KATRINA', 10.5],
        ['SILVER TEQUILA EL CHARRO', 18],
        ['GOLD TEQUILA EL CHARRO', 18],
        ['REPOSADO TEQUILA EL CHARRO', 25],
        ['BLANCO JOSE CUERVO', 50],
        ['ORO JOSE CUERVO', 50],
        ['DURAZNO ZHUMIR', 4.5],
        ['COCO ZHUMIR', 4.5],
        ['CRANBERRY ZHUMIR', 4.5],
        ['PINK ZHUMIR', 9],
        ['SECO ZHUMIR', 4.5],
        // TEQUILAS 2
        ['750 CRISTAL CLASICO', 10],
        ['PEQUEÑO CRISTAL CLASICO', 5.25],
        ['BOMB PEACH CRISTAL', 5.5],
        ['750 CRISTAL SECO', 7.5],
        ['PEQUEÑO CRISTAL SECO', 4],
        ['750 TROPICO SECO', 7.5],
        ['PEQUEÑO TROPICO SECO', 4],
        ['PEQUEÑA NEGRA CAÑA MANABITA', 3.75],
        ['PEQUEÑA ROJA CAÑA MANABITA', 4.25],
        ['LITRO AZUL ANTIOQUEÑO', 18],
        ['750 AZUL ANTIOQUEÑO', 15],
        ['PEQUEÑO AZUL ANTIOQUEÑO', 9],
        ['750 NORTEÑO', 7],
        ['PEQUEÑO NORTEÑO', 4],
        ['750 ROJA CAÑA MANABITA', 8.25],
        ['TUBO ROJA CAÑA MANABITA', 12],
        ['TUBO NEGRA CAÑA MANABITA', 12],
        ['750 NEGRA CAÑA MANABITA', 7],
        ['CAÑA ROSE', 6],
        ['750 VERDE CAÑA MANABITA', 6],
        ['K-LEÑO', 10.5],
        // VINOS 1
        ['LATA MORA ANTHONY', 1.75],
        ['LATA FRAMBUESA ANTHONY', 1.75],
        ['LATA MORA LIGHT ANTHONY', 1.75],
        ['LATA FRESA ANTHONY', 1.75],
        ['SANGRIA FIESTA BRAVA', 5.5],
        ['VIÑA DEL MAR ESPUMANTE', 4],
        ['GRAND DUVAL ESPUMANTE', 6.5],
        ['VENETTO', 3.5],
        ['EL FRAILE', 4],
        ['FRAMBUESA ANTHONY', 8],
        ['MORA ANTHONY', 8],
        ['ROSA GRAND VANDUSH', 7.5],
        ['AZUL GRAND VANDUSH', 7.5],
        ['LA PARRA VINO', 4.75],
        ['MANZANA BOONES', 8],
        ['CRUZARES VINO', 4.5],
        ['LA CATEDRA VINO', 4.5],
        ['CATADOR VINO', 4.5],
        ['CAPRICCIO NOVECENTO ESPUMANTE', 12],
        ['LA VID BLEND VINO', 4.5],
        ['VIEJO VIÑEDO VINO', 4.5],
        ['MALBEC RESERVADO VINO', 8],
        // VINOS 2
        ['BORDEAUX CALVET VINO', 8],
        ['MERLOT CALVET VINO', 9],
        ['CABERNET SAUVIGNON CALVET VINO', 8],
        ['BORDEAUX CALVET RESERVE VINO', 13],
        ['BLANCO CHIARLI MIO ESPUMANTE', 7],
        ['ROSATO CHIARLI MIO ESPUMANTE', 7],
        ['MARIA ROSATO LAMBRUSCO VINO', 8],
        ['MARIA ROSSO LAMBRUSCO VINO', 8],
        ['ROSSO CHIARLI MIO ESPUMANTE', 7],
        ['ANTONIO ROSATO LAMBRUSCO', 11.5],
        ['ANTONIO LAMBRUSCO', 10],
        ['GOLD BLUE NUN ESPUMANTE', 15],
        ['ROSE BLUE NUN ESPUMANTE', 15],
        ['PETER MERTES LIEBFRAUMILCH', 7],
        ['KLAUS LANGHOFF', 7],
        ['BLUE NUN RIVANER', 14],
        ['ALTA GAMMA VINO', 5],
        ['MIRAFLORES VINO', 5],
        ['BLANCO TOCORNAL VINO', 7],
        ['MERLOT TOCORNAL VINO', 7],
        ['CABERNET SAUVIGNON TOCORNAL VINO', 7],
        ['MERLOT BICICLETA VINO', 10],
        ['CABERNET SAUVIGNON BICICLETA VINO', 10],
        ['VIÑA MAIPO VINO', 8],
        ['FRONTERA VINO', 9.5],
        ['MERLOT RESERVADO VINO', 8],
        ['CABERNET SAUVIGNON RESERVADO VINO', 10],
        ['UNDURRAGA VINO', 10],
        ['GATO NEGRO VINO', 6.25],
        ['CASILLERO DEL DIABLO VINO', 18],
        // WHISKY 1
        ['ZANDER WHISKY', 5],
        ['GENIO WHISKY', 5],
        ['SPECIAL QUEEN WHISKY', 5.75],
        ['MR ALLEN WHISKY', 5.75],
        ['ASTILLA DE ROBLE WHISKY', 6],
        ['CARTAGO WHISKY', 6.6],
        ['750 BLACK JOHN MORRIS', 12],
        ['LITRO BLACK JOHN MORRIS', 14],
        ['750 BLUE JOHN MORRIS', 12],
        ['LITRO BLUE JOHN MORRIS', 14],
        ['GLENGOLD', 7],
        ['MANZANA CLENGOLD', 7.5],
        ['PIÑA CLENGOLD', 7.5],
        ['LITRO ROJO OLD TIMES', 12],
        ['750 ROJO OLD TIMES', 10],
        ['750 NEGRO OLD TIMES', 14.5],
        ['BELLOWS WHISKY', 12],
        ['RED WILLIAMS', 10],
        ['BLACK WILLIAMS', 13],
        // WHISKY 2
        ['GUITARRA TRIBUTE WHISKY', 17],
        ['BLANCO JACK DANIELS WHISKY', 65],
        ['NEGRO JACK DANIELS WHISKY', 65],
        ['JAGERMEITER WHISKY', 30],
        ['BLACK OWL', 15],
        ['ROYAL BLEND', 9.5],
        ['JAMES KING', 10],
        ['CLAN MAC GREGOR', 10],
        ['ROJO JOHN BARR', 12],
        ['NEGRO JOHN BARR', 15],
        ['LITRO LABEL 5', 11],
        ['750 LABEL 5', 14],
        ['WILLIAM LAWSONS', 13],
        ['BLACK AND WHITE', 17.5],
        ['ROJO GRANTS', 18.5],
        ['NARANJA GRANTS', 21],
        ['AZUL GRANTS', 24],
        ['VERDE GRANTS', 38.5],
        // WHISKY 3
        ['BALLANTINES WHISKY', 20],
        ['PASSPORT SELECTION', 13],
        ['LITRO SOMETHING SPECIAL', 24.5],
        ['750 SOMETHING SPECIAL', 20.5],
        ['LITRO SANDY MAC', 24],
        ['750 SANDY MAC', 19],
        ['LITRO ROJO JOHNNIE WALKER', 33],
        ['750 ROJO JOHNNIE WALKER', 26],
        ['DOUBLE BLACK JOHNNIE WALKER', 72],
        ['DORADO JOHNNIE WALKER', 95],
        ['GREEN LABEL JOHNNIE WALKER', 107],
        ['CHIVAS REGAL', 45],
        ['BUCHANANS DELUXE', 56],
        ['BUCHANANS MASTER', 66],
        ['SWING', 88],
        ['LITRO GRAND OLD PARR WHISKY', 62],
        ['750 GRAND OLD PARR WHISKY', 51],
    ];
}
