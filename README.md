# BCI-Central Take Home Assignment

Dear Recruitment Team,

This is **[Arbha Pradana](https://linkedin.com/in/arbhapr)** final result of technical test following the document you gave me on email with some main criterias, for position **Backend Developer (PHP)** in **BCI Central**.

This project is created using **[Symfony Framework](https://symfony.com)**, and to be honest, this is my first time created project using **[Symfony](https://symfony.com)**.

## Prerequisites
1. Your favorite code editor to check the source code, especially the one who support many extensions to help code more effectively and efficiently, here is my recommendations, **[Visual Studio Code](https://code.visualstudio.com/download)**

2. You need to have the **Symfony CLI** installed to run on the local-server using Symfony CLI, for more information you can see the **[Symfony official website](symfony.com/download)**.

3. You need to install **PostgreSQL** and use your favorite DBMS to check the data inserted from this application, here is my favorite DBMS for recommendation, **[DBeaver](https://dbeaver.io/download/)**.

## How To Install
1. You need to clone this repository

   ```bash
   git clone https://github.com/arbhapr/bci-technical
   ```
   
2. Create a new database using PostgreSQL in your favorite DBMS, with name ```bci-technical```.
3. If you want to direct migrating the migration to your database, you can proceed with the following command
   ```bash
    php bin/console doctrine:migrations:migrate
   ```
4. When there are no error, you can proceed to start your local-server using Symfony CLI by typing the command
   ```bash
   symfony server:start
   ```
5. Voila, you will see the first page of Symfony by opening **http://127.0.0.1:8000** on default port.
6. To access the endpoint following the main point of the assignments, you can proceed to this **[Postman Documentation BCI-Technical Test](https://documenter.getpostman.com/view/7480974/2sAXjNXqvv)** documentation.

## Credits
1. Myself, **[Arbha Pradana](https://linkedin.com/in/arbhapr)**.
2. Thanks to **Recruitment Team** of **[BCI-Central](https://www.bcicentral.com/)** to allow me following this challenging technical test.
3. Thanks to **[ChatGPT](https://chatgpt.com)** to help me understanding the code basis, the fundamental and the enhancement of **Symfony** framework.
4. The tools that I mentioned above, **[Visual Studio Code](https://code.visualstudio.com/)**, **[DBeaver](https://dbeaver.io/)**, and the **[Symfony Framework](symfony.com/)**.

I think that's all the README.MD of this project, hoping for the best response from **[BCI-Central](https://www.bcicentral.com/)** team for the next step.

Best regards.