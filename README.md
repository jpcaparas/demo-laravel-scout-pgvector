# Similarity Search with Laravel Scout and pgvector

- First off, I want to thank Ben Bjurstrom for creating the `pgvector-scout` package. This package is a Laravel Scout driver for the `pgvector` extension, which isn't supported out of the box by Laravel Scout.
- This project is meant to be demo'ed using Laravel Sail
- This project demonstrates the use of `pgvector` driven similarity search in Laravel Scout using the `pgvector-scout` package.
- This project uses the `openai` API to generate vectors for the `Restaurant` model. The `openai` API is not free, so you will need to supply your own API key.
- When running scout searches, the `pgvector-scout` package will use the `pgvector` extension to perform similarity searches on the `vector` column of the `Restaurant` model. This will then make calls to the `openai` API to generate vectors for the search query and then perform the similarity search in the database.
- Copy the `.env.example` to `.env`. Most, if not all, the defaults should work out of the box with Laravel Sail.
- Supply an `OPENAI_API_KEY` in the `.env`
- Run `./vendor/bin/sail up`
- Run `sail artisan key:generate`
- Run `sail artisan migrate:fresh --seed`
- On a separate TTY, run `sail artisan queue:work --queue=scout,default` (this can be made faster with Horizon)
- On a separate TTY, run `sail artisan scout:import "App\Models\Restaurant"`
- Enter a Tinker REPL with `sail artisan tinker`
- Run `App\Models\Restaurant::search('Nigiri')->get()` and you should see a Sushi restaurant because _Nigiri_ is a type of Sushi. Even though _Nigiri_ is not in the name of the restaurant, the similarity search will return it because the `openai` API will generate a vector for _Nigiri_ and the similarity search will find the closest match in the database.
- Likewise, running `App\Models\Restaurant::search('Windy City')->get()` will return a Chicago-style pizza restaurant called _Mama's Kitchen_, as _Windy City_ is a nickname for Chicago, and the openai API will generate a vector for _Windy City_ and the similarity search will find the closest match in the database.