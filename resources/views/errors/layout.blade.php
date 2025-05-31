<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            user-select: none;
        }
        .app {
            position: relative;
            height: 100svh;
            background: #050505;
            overflow: hidden;
            font-family: "Montserrat", sans-serif;
            color: #fff;
            font-size: 18px;
        }
        .img {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            height: 100vh;
            bottom: 0;
            max-width: 1920px;
            margin: 0 auto;
            animation: catAnim 1.5s ease forwards;
        }
        .img img {
            display: flex;
            width: 50%;
            height: 50%;
            object-fit: cover;
        }
        @keyframes catAnim {
            0% {
                translate: 0 50%;
            }
            100% {
                translate: 0 0;
            }
        }
        .okak {
            position: absolute;
            bottom: 0px;
            left: 50%;
            translate: -50% 0;
            font-weight: 900;
            font-size: 40px;
        }
        .error {
            position: absolute;
            top: 50%;
            left: 50%;
            font-size: 500px;
            font-weight: 900;
            transform: translate(-50%, 1500px);
            animation: errorAnim 1.5s ease forwards;
        }
        @keyframes errorAnim {
            100% {
                transform: translate(-50%, -70%);
            }
        }

        /* Tablet styles */
        @media screen and (max-width: 1024px) {
            .error {
                font-size: 350px;
            }
            .okak {
                font-size: 32px;
            }
            .img img {
                width: 60%;
                height: 60%;
            }
        }

        /* Mobile landscape */
        @media screen and (max-width: 768px) {
            .error {
                font-size: 250px;
            }
            .okak {
                font-size: 28px;
            }
            .img img {
                width: 65%;
                height: 65%;
            }
        }

        /* Mobile portrait */
        @media screen and (max-width: 480px) {
            .error {
                font-size: 180px;
            }
            .okak {
                font-size: 24px;
            }
            .img img {
                width: 70%;
                height: 70%;
            }
        }

        /* Small mobile */
        @media screen and (max-width: 320px) {
            .error {
                font-size: 140px;
            }
            .okak {
                font-size: 20px;
            }
            .img img {
                width: 80%;
                height: 80%;
            }
        }

        /* Height adjustments for short screens */
        @media screen and (max-height: 600px) {
            .error {
                font-size: 200px;
            }
            .okak {
                font-size: 24px;
            }
            .img img {
                width: 60%;
                height: 60%;
            }
        }

        @media screen and (max-height: 400px) {
            .error {
                font-size: 120px;
            }
            .okak {
                font-size: 18px;
            }
            .img img {
                width: 50%;
                height: 50%;
            }
        }
    </style>
</head>
<body>
    <div class="app">
        <div class="error">
            @yield('code')
        </div>
        <div class="img">
            <img src="{{ asset('images/cat.png') }}" alt="cat">
            <h1 class="okak">ОКАК</h1>
        </div>
    </div>
</body>
</html>
