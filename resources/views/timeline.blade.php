@extends('layouts.app')

@section('title', env('APP_NAME') . ' - Timeline')

@section('content')

<div style="text-align: center">
    <p><keycode>↑</keycode> to go to newer toots. <keycode>↓</keycode> to go to older toots. <keycode>S</keycode> to listen to the toot.</p>
</div>

<div class="pd">
        <div class="pdl">
        <div class="poak close" id="poak">
            <img src="/poak.png">
        </div>
            <div class="pdl-buttons">
                <div class="pdl-buttons-camera">
                </div>
                <div class="pdl-buttons-circles">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
            <div class="pdl-container">
                <div class="pdl-screenwrap">
                    <div class="pdl-screen-buttons">
                        <div></div>
                        <div></div>
                    </div>

                    <div class="pdl-screen screen" id="toot">
                        @foreach ($toots as $index => $toot)
                            <span data-index="{{ $index }}" data-id="{{ $toot['id'] }}" @if($index === 0) class="current-toot" @endif>
                                {!! $toot['content'] !!}
                                <p><a href="{{ $toot['url'] }}">{{ \Carbon\Carbon::parse($toot['created_at'])->diffForHumans() }}</a></p>
                            </span>
                        @endforeach
                    </div>

                    <div class="pdl-screen-bottom">
                        <div class="pdl-screen-bottom-circle"></div>
                        <div>☰</div>
                    </div>
                </div>
            </div>
            <div class="pdl-bottom">
                <div>
                    <div class="pdl-bottom-bb" id="toot-to-speech">
                        <svg class="icon" style="width: 25px; height: 25px;color:white">
                            <use xlink:href="#speak"></use>
                        </svg>
                    </div>
                </div>
                <div class="pdl-bottom-middle">
                    <div class="pdl-bottom-lines">
                        <div class="pdl-bottom-line"></div>
                        <div class="pdl-bottom-line"></div>
                    </div>
                    <div class="pdl-bottom-big">

                    </div>
                </div>
                <div class="pdl-bottom-dpad-wrap">
                    <div class="pdl-bottom-dpad">
                        <div></div>
                        <div class="dpad-up" id="toot-up">
                            <svg class="icon" style="width: 25px; height: 25px;color:white">
                                <use xlink:href="#up"></use>
                            </svg>
                        </div>
                        <div></div>
                        <div class="dpad-left"></div>
                        <div class="dpad-middle"></div>
                        <div class="dpad-right"></div>
                        <div></div>
                        <div class="dpad-down" id="toot-down">
                            <svg class="icon" style="width: 25px; height: 25px;color:white">
                                <use xlink:href="#down"></use>
                            </svg>
                        </div>
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="pdr">
            <div class="pdr-screenwrap">
                <div class="pdr-screen screen">
                    <div class="profile">
                        <div class="profile-image">
                            @foreach ($toots as $index => $toot)
                                <img data-index="{{ $index }}" class="avatar @if($index === 0) current-toot @endif" src="{!! $toot['account']['avatar'] !!}">
                            @endforeach
                        </div>
                        <div class="profile-data">
                            <div class="profile-data-name">
                                @foreach ($toots as $index => $toot)
                                    <span data-index="{{ $index }}" @if($index === 0) class="current-toot" @endif>
                                        {!! $toot['account']['display_name'] !!}<br>
                                        <a href="{!! $toot['account']['url'] !!}" target="_blank" rel="noopener">profile</a><br>
                                        {!! $toot['account']['note'] !!}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pdr-buttonwrap">
                <div class="pdr-buttons">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
            <div class="pdr-bottom">
                <div class="pdr-left">
                    <div class="pdr-left-white">
                        <div></div>
                        <div></div>
                    </div>
                    <div></div>
                </div>
                <div class="pdr-right">
                    <div class="pdr-bottom-lines">
                        <div class="pdr-bottom-line"></div>
                        <div class="pdr-bottom-line"></div>
                    </div>
                    <div>
                        <div class="pdr-bottom-button"></div>
                    </div>
                </div>
            </div>
            <div class="pdr-bottom-bigones">
                <div></div>
                <div></div>
            </div>
            <div></div>
        </div>
    </div>

    <script>
    (function () {
        const maxToots = {{ count($toots) }} - 1
        let currentTootIndex = 0
        let msg = new SpeechSynthesisUtterance()
        const poak = document.getElementById('poak')

        const goUp = () => {
            currentTootIndex--
            if (currentTootIndex < 0) {
                currentTootIndex = maxToots
            }
            Array.from(document.querySelectorAll('[data-index]')).forEach(e => {
                e.classList.remove('current-toot')
                e.dataset.index == currentTootIndex ? e.classList.add('current-toot') : null
            })
        }

        const goDown = () => {
            currentTootIndex++
            if (currentTootIndex > maxToots) {
                currentTootIndex = 0
            }
            Array.from(document.querySelectorAll('[data-index]')).forEach(e => {
                e.classList.remove('current-toot')
                e.dataset.index == currentTootIndex ? e.classList.add('current-toot') : null
            })
        }

        document.addEventListener("keyup", (e) => {
            const UP = 38
            const DOWN = 40
            const S = 83
            switch (e.keyCode)
            {
                case UP:
                    goUp();
                    break;
                case DOWN:
                    goDown();
                    break;
                case S:
                    document.getElementById('toot-to-speech').click()
                    break;
                default:
                    break;
            }
        })
        document.getElementById('toot-to-speech').addEventListener('click', function () {
            const originalToot = document.querySelector('#toot .current-toot').innerHTML
            const toot = document.querySelector('#toot .current-toot')
            const content = toot.innerText
            const tootWords = content.split(' ')
            let currentBoundary = 0
            toot.innerText = ''
            poak.classList.remove('close')
            msg.text = content
            msg.addEventListener("end", (event) => {
                poak.classList.add('close')
                document.querySelector('#toot .current-toot').innerHTML = originalToot
            })

            msg.addEventListener("boundary", (event) => {
                currentBoundary++
                toot.innerHTML = `<p>${tootWords.slice(0, currentBoundary).join(' ')}</p>`
            });
            window.speechSynthesis.speak(msg)
        })

        document.getElementById('toot-up').addEventListener('click', function() {
            goUp()
        })

        document.getElementById('toot-down').addEventListener('click', function() {
            goDown()
        })
    })();
    </script>

    <svg width="0" height="0" style="display: none">
        <symbol xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" id="up">
            <defs/>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round">
                <g id="Artboard-4" transform="translate(-488.000000, -1046.000000)" stroke="currentColor" stroke-width="2">
                    <g id="Extras" transform="translate(48.000000, 1046.000000)">
                        <g id="up" transform="translate(440.000000, 0.000000)">
                            <path d="M12,4 L12,21" id="Path-58"/>
                            <polyline id="Path-59" stroke-linejoin="round" transform="translate(12.000000, 7.000000) scale(-1, 1) translate(-12.000000, -7.000000) " points="4 11 12 3 20 11"/>
                        </g>
                    </g>
                </g>
            </g>
        </symbol>
        <symbol xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" id="down">
            <defs/>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round">
                <g id="Artboard-4" transform="translate(-532.000000, -1046.000000)" stroke="currentColor" stroke-width="2">
                    <g id="Extras" transform="translate(48.000000, 1046.000000)">
                        <g id="down" transform="translate(484.000000, 0.000000)">
                            <path d="M12,3 L12,20" id="Path-58"/>
                            <polyline id="Path-59" stroke-linejoin="round" transform="translate(12.000000, 17.000000) scale(-1, -1) translate(-12.000000, -17.000000) " points="4 21 12 13 20 21"/>
                        </g>
                    </g>
                </g>
            </g>
        </symbol>
        <symbol
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="24" width="24" id="speak">
            <g>
                <path d="M22.8525 7.428749999999999H24v9.1425h-1.1475Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M21.7125 16.57125h1.1400000000000001v1.1400000000000001h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M21.7125 6.28125h1.1400000000000001v1.1475h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M20.572499999999998 17.71125h1.1400000000000001v1.1400000000000001h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M20.572499999999998 9.70875h1.1400000000000001v4.574999999999999h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M20.572499999999998 5.14125h1.1400000000000001v1.1400000000000001h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M19.424999999999997 14.283750000000001h1.1475v1.1400000000000001H19.424999999999997Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M19.424999999999997 8.568750000000001h1.1475v1.1400000000000001H19.424999999999997Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M18.285 10.85625h1.1400000000000001v2.2874999999999996h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M15.997499999999999 4.00125h1.1400000000000001v15.997499999999999h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M14.857499999999998 19.99875h1.1400000000000001v1.1400000000000001h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M14.857499999999998 2.8537500000000002h1.1400000000000001v1.1475h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M11.43 21.138749999999998h3.4275v1.1475h-3.4275Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M12.57 16.57125h1.1400000000000001v1.1400000000000001h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M12.57 6.28125h1.1400000000000001v1.1475h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M11.43 1.71375h3.4275v1.1400000000000001h-3.4275Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M11.43 7.428749999999999h1.1400000000000001v9.1425h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M10.2825 19.99875h1.1475v1.1400000000000001h-1.1475Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M10.2825 2.8537500000000002h1.1475v1.1475h-1.1475Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M9.1425 18.85125h1.1400000000000001v1.1475h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M9.1425 4.00125h1.1400000000000001v1.1400000000000001h-1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M7.995 17.71125h1.1475v1.1400000000000001h-1.1475Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M7.995 5.14125h1.1475v1.1400000000000001h-1.1475Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M6.855 16.57125h1.1400000000000001v1.1400000000000001H6.855Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M6.855 6.28125h1.1400000000000001v1.1475H6.855Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M5.715 15.423750000000002h1.1400000000000001v1.1475H5.715Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M5.715 7.428749999999999h1.1400000000000001v1.1400000000000001H5.715Z" fill="currentColor" stroke-width="0.75"></path>
                <path d="M5.715 8.568750000000001h-4.574999999999999v1.1400000000000001H0v4.574999999999999h1.1400000000000001v1.1400000000000001h4.574999999999999Zm-2.2874999999999996 2.2874999999999996H2.2874999999999996v2.2874999999999996H1.1400000000000001v-2.2874999999999996h1.1475v-1.1475h1.1400000000000001Z" fill="currentColor" stroke-width="0.75"></path>
            </g>
        </symbol>
    </svg>

@endsection
