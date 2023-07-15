## Feladatmegoldás

#### 1. A feladat php 8.2 php és Larave 10-es keretrendszerre épül. Az adatbázis mysql.

#### 2. Felépités
    Négy táblára van szükség.
    - Márkák (brands)
    - Termékek (products)
    - Raktárak (storages)
    - Termék - raktár kapcsoló táblára, amely tartalmazza a tárolt mennyiséget.

Az alapvető osztályok a `Eloquent Model` -ek, a base `Model` a `Product` erre épül a `Book`, és `Car` osztály.
A `Product` osztályban vannak definiálva azok a függvények amelyeket használnak a `Book` és `Car` osztály is.

A márka feltöltést route-ról hivható de a `Faker` tölti fel, nem szükség semmit megadni neki, viszont van seeder is amely feltölti a márka és raktár osztályokat.

A raktár tartalmának lekérdezését szintén hivható route -ról, vissza adja a raktárakat és a bennük levő termékeket. `Feature` teszt is van rá.

A termék hozzáadása `szintén` routeról hivható, itt viszont szükséges megadni postban az értékeket (név stb), viszont van erre is Feature teszt is amely hozzáad két terméket.

Egy termék lekérése több raktárral szintén tesztből hivható (test_return_a_products_with_multiple_storage)

Továbbá találhó még olyan teszt amely azt vizsgálja hogyha nagyobb értéket akarunk hozzáadni mint amennyi össze raktár kapacitásunk van, illetve egy bizonyos termék darabszámának a csökkentésére is található két teszt, az egyik azt vizsgála hogy csak egy raktárban kell csökkenteni az értéket a másik hogyha több raktárban.

Található két `Storage Feature` teszt is amely a raktár kapacitást vizsgálja.

A raktár vizsgálatok amelyet a `BookController` és `CarController` is használni tud egy közös osztályban vannak megoldva.

#### 3. Project futtatása
- composerrel telepithetőek a függőségek
- .env helyes beállitása után az adatbázis telepitése követezik, `php artisan migrate` paranccsal futtatható, érdemes a tesztekre külön adatbázist használni ez a `.env.testing` -el érhető el
- seederek futtatása: `php artisan db:seed`

