# The Resonate Wealth Game 
### A Bettergist Project
#### Sponsored by PHP Experts, Inc.
#### Bettergist: Better worlds through humanitarian code.

Expand your subconscious limitations that are preventing you from accumulating
and -- most impotantly -- *keeping* large amounts of money.

Based upon legions of psychological research, everyone has a baseline limit
for how much money they can reasonably accumulate, and keep, over a period of
time. One term of this is "your upper savings limit".

Have you ever struggled to keep $1,000 in the bank? Living paycheck to paycheck
despite a lucrative career? Or just can never seem to get that amazing opportunity?
It could very well be because you are, in the words of Richard Kioysaki, 
"thinking poorly".

This game aims to rewire your subconscious internal programming to be more in line
with the programming of people in the Top 1%. By trying to figure out what to do
with increasing amounts of money every day, you will not only learn new things about yourself
but also manifest a new future reality as you literally imagine what you would do with $10,000,
$500,000, even $50,000,000 in spare cash on hand.

Expand your limits today!!

---

This work is founded on the principles of
* The Secret,
* The Celestine Prophecy,
* and The Teachings of Abraham, as channeled by Esther and Bill Hicks.

Particular insight and inspiration came from the Ra collective intelligence, in The Ra Material.
The actual metaphysics of how this works revolves around "The Law of One". 

## Installation

To get started, just do this:

Manually:

    sudo -u postgres createuser --pwprompt resonator
    sudo -u postgres createdb --owner=resonator resonate_wealth

    composer create-project bettergist/resonate-wealth resonate-wealth
    cp .env.example .env
    # Edit .env and set your database credentials.
    chmod -R 0777 bootstrap/cache storage 
    php artisan key:generate
    php artisan migrate
    php artisan db:seed
    
Via Docker:

    composer create-project bettergist/resonate-wealth resonate-wealth
    ./install.sh

Follow the prompts. And you'll be done in under 5 minutes! A complete Linux,
Nginx, PostgreSQL and PHP (LEPP) stack + Redis. MariaDB and Memcache will be
coming soon!

## Features: ##

1. Gives you a structured opportunity to imagine and manifest a life of incredible riches.
2. Learn more about yourself: Are you a profiteer, adventurer, Volunteer?
3. Engage your creative and logical sides of your brain wholistically, which will
increase your core latent abilities in all areas of life.
4. Have fun!

## Inspiration ##

I was inspired to make this game back on 12 November 2015 while apprenticing my
then-girlfriend into how to quickly and effortlessly achieve her dual goals of
expanding both her modelling and actress careers and establishing her own business.

I was inspired to create this game in December 2020 to aid my apprentices.

## Contributors

[Theodore R. Smith](https://www.phpexperts.pro/]) <theodore@phpexperts.pro>  
GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690  
CEO: PHP Experts, Inc.

## License

This project is licensed under the [Creative Commons Attribution License v4.0 International](LICENSE.cc-by.md).

![CC.by License Summary](https://user-images.githubusercontent.com/1125541/93617603-cd6de580-f99b-11ea-9da4-f79c168c97df.png)
