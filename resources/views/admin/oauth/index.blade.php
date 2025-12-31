@extends('admin.layout_blank')

@section('title', $admin_page_title)
@include('flash::message')
@section('page_css')
    <style>
        .base-timer {
            position: relative;
            width: 300px;
            height: 300px;
        }

        .base-timer__svg {
            transform: scaleX(-1);
        }

        .base-timer__circle {
            fill: none;
            stroke: none;
        }

        .base-timer__path-elapsed {
            stroke-width: 7px;
            stroke: grey;
        }

        .base-timer__path-remaining {
            stroke-width: 7px;
            stroke-linecap: round;
            transform: rotate(90deg);
            transform-origin: center;
            transition: 1s linear all;
            fill-rule: nonzero;
            stroke: currentColor;
        }

        .base-timer__path-remaining.green {
            color: rgb(65, 184, 131);
        }

        .base-timer__path-remaining.orange {
            color: orange;
        }

        .base-timer__path-remaining.red {
            color: red;
        }

        .base-timer__label {
            position: absolute;
            width: 300px;
            height: 300px;
            top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
        }

        /*Page height and footer fix*/
        .enlarged .footer {
            left: 0px !important;
        }

        body.enlarged {
            min-height: 100px !important;
        }

        #wrapper {
            height: auto !important;
        }

    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h4>Please <span style="color:red">dont</span> close this window while we get the necessary information from
                <strong>Aweber</strong>.
                This page will be automatically closed in 5 sec........
            </h4>
        </div>
    </div>
    <div class="row d-flex justify-content-center">
        <div id="app"></div>
    </div>
@stop

@section('page_js')
    <script>
        // Credit: Mateusz Rybczonec
        const FULL_DASH_ARRAY = 283;
        const WARNING_THRESHOLD = 10;
        const ALERT_THRESHOLD = 10;
        const COLOR_CODES = {
            info: {
                color: "green"
            },
            warning: {
                color: "orange",
                threshold: WARNING_THRESHOLD
            },
            alert: {
                color: "red",
                threshold: ALERT_THRESHOLD
            }
        };

        const TIME_LIMIT = 5;
        let timePassed = 0;
        let timeLeft = TIME_LIMIT;
        let timerInterval = null;
        let remainingPathColor = COLOR_CODES.info.color;
        const refreshToken = '{{ $refresh_token }}';
        const accessToken = '{{ $access_token }}';
        const account_id = '{{ $account_id }}';
        function onTimesUp() {
            const message = {
                accessToken: accessToken,
                refreshToken: refreshToken,
                account_id: account_id
            };
            window.opener.setTokens(message);
            clearInterval(timerInterval);
            window.close();
        }

        function startTimer() {
            timerInterval = setInterval(() => {
                timePassed = timePassed += 1;
                timeLeft = TIME_LIMIT - timePassed;
                document.getElementById("base-timer-label").innerHTML = formatTime(
                    timeLeft
                );
                setCircleDasharray();
                setRemainingPathColor(timeLeft);

                if (timeLeft === 0) {
                    onTimesUp();
                }
            }, 1000);
        }

        function formatTime(time) {
            const minutes = Math.floor(time / 60);
            let seconds = time % 60;

            if (seconds < 10) {
                seconds = `0${seconds}`;
            }

            return `${minutes}:${seconds}`;
        }

        function setRemainingPathColor(timeLeft) {
            const {
                alert,
                warning,
                info
            } = COLOR_CODES;
            if (timeLeft <= alert.threshold) {
                document
                    .getElementById("base-timer-path-remaining")
                    .classList.remove(warning.color);
                document
                    .getElementById("base-timer-path-remaining")
                    .classList.add(alert.color);
            } else if (timeLeft <= warning.threshold) {
                document
                    .getElementById("base-timer-path-remaining")
                    .classList.remove(info.color);
                document
                    .getElementById("base-timer-path-remaining")
                    .classList.add(warning.color);
            }
        }

        function calculateTimeFraction() {
            const rawTimeFraction = timeLeft / TIME_LIMIT;
            return rawTimeFraction - (1 / TIME_LIMIT) * (1 - rawTimeFraction);
        }

        function setCircleDasharray() {
            const circleDasharray = `${(calculateTimeFraction() * FULL_DASH_ARRAY
                                    ).toFixed(0)} 283`;
            document
                .getElementById("base-timer-path-remaining")
                .setAttribute("stroke-dasharray", circleDasharray);
        }

        window.onload = (event) => {
            document.getElementById("app").innerHTML = `<div class="base-timer">
                                                        <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                                            <g class="base-timer__circle">
                                                            <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"></circle>
                                                            <path
                                                                id="base-timer-path-remaining"
                                                                stroke-dasharray="283"
                                                                class="base-timer__path-remaining ${remainingPathColor}"
                                                                d="
                                                                M 50, 50
                                                                m -45, 0
                                                                a 45,45 0 1,0 90,0
                                                                a 45,45 0 1,0 -90,0
                                                                "
                                                            ></path>
                                                            </g>
                                                        </svg>
                                                        <span id="base-timer-label" class="base-timer__label">${formatTime(
                                                            timeLeft
                                                        )}</span>
                                                        </div>
`;
            startTimer();
        };
    </script>
@endsection
