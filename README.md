
# Installation

## TestApp

Required:
- PHP 8.1+
- Mysql

#You need
- create DataBase 'test_devport' in MySql
- sudo apt-get install php8.1-gd

## For Localhost

go to folder afore project and set permissions:
- sudo chgrp -R www-data test_app.loc/
- sudo chmod -R 775 test_app.loc/storage/
- sudo chmod -R 777 test_app.loc/storage/logs

1) in project create .env and fill it from .env.example
2) copy .env.example to .env and set properties for your db
   in .env Fill next fields with your local data:
```
   * DB_DATABASE=test_devport
   * DB_USERNAME=***
   * DB_PASSWORD=***
```
3) run next commands:
```
- composer install
- php artisan key:generate
- php artisan migrate
- php artisan db:seed
- npm install
- npm run dev
```


Завдання:

1) На главной странице необходимо вывести форму регистрации с такими полями: Username, Phonenumber и кнопку Register.
   После того как пользователь заполнил поля и нажал кнопку Register, происходит следующее: Пользователь получает сгенерированный уникальный линк на специальную страницу (страница А), доступ к которой будет доступен по уникальной ссылке в течении 7 дней. После истечении времени, линк становится недействительным.
2) Функционал страницы А:
   • Возможность сгенерировать новый уникальный линк
   • Возможность деактивировать данный уникальный линк
   • Кнопка Imfeelinglucky
   • Кнопка History
   • По нажатию на кнопку "Imfeelinglucky" пользователю выводится:
   • Рандомное число от 1 до 1000. Результат Win/Lose. Сумма выиграша (0 если проигрыш)
   • Если рандомное число четное- выводить пользователю результат Win. В противном случае результат Lose.
   • Сумма Win. Если рандомное число более 900, сумма выигрыша должна составлять 70% от рандомного числа. Если рандомное число более 600, сумма выигрыша должна составлять 50% от рандомного числа. Если рандомное число более 300, сумма выигрыша должна составлять 30% от рандомного числа. Если рандомное число меньше или равно 300, сумма выигрыша должна составлять 10% от рандомного числа.
   • По нажатию на кнопку "History" пользователю выводиться:
   • Информация о последних 3х результатах нажатия на кнопку "Imfeelinglucky"

3) Административная часть приложения (не обязательно)
   Добавить возможность создавать, просматривать, редактировать, удалять пользователей
