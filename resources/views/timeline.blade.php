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
                            <span data-id="{{ $index }}" @if($index === 0) class="current-toot" @endif>
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
                            <use xlink:href="#speech"></use>
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
                                <img data-id="{{ $index }}" class="avatar @if($index === 0) current-toot @endif" src="{!! $toot['account']['avatar'] !!}">
                            @endforeach
                        </div>
                        <div class="profile-data">
                            <div class="profile-data-name">
                                @foreach ($toots as $index => $toot)
                                    <span data-id="{{ $index }}" @if($index === 0) class="current-toot" @endif>
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
            Array.from(document.querySelectorAll('[data-id]')).forEach(e => {
                e.classList.remove('current-toot')
                e.dataset.id == currentTootIndex ? e.classList.add('current-toot') : null
            })
        }

        const goDown = () => {
            currentTootIndex++
            if (currentTootIndex > maxToots) {
                currentTootIndex = 0
            }
            Array.from(document.querySelectorAll('[data-id]')).forEach(e => {
                e.classList.remove('current-toot')
                e.dataset.id == currentTootIndex ? e.classList.add('current-toot') : null
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
            console.log(msg.text)
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
        <symbol xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1" id="speech">
            <defs/>
            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="Artboard-4" transform="translate(-136.000000, -599.000000)">
                    <g id="223" transform="translate(136.000000, 599.000000)">
                        <path d="M5.58578644,18 L8.29289322,20.7071068 L9,21.4142136 L9.70710678,20.7071068 L12.4142136,18 L20.0024554,18 C21.1070219,18 22,17.1024207 22,15.9975267 L22,6.00247329 C22,4.90415942 21.1082892,4 20.0066023,4 L3.99339768,4 C2.89058031,4 2,4.89859975 2,6.00247329 L2,15.9975267 C2,17.0970743 2.89547708,18 3.99895656,18 L5.58578644,18 Z" id="Rectangle-406" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        <path d="M8,12 C7.44771525,12 7,11.5522847 7,11 C7,10.4477153 7.44771525,10 8,10 C8.55228475,10 9,10.4477153 9,11 C9,11.5522847 8.55228475,12 8,12 Z M12,12 C11.4477153,12 11,11.5522847 11,11 C11,10.4477153 11.4477153,10 12,10 C12.5522847,10 13,10.4477153 13,11 C13,11.5522847 12.5522847,12 12,12 Z M16,12 C15.4477153,12 15,11.5522847 15,11 C15,10.4477153 15.4477153,10 16,10 C16.5522847,10 17,10.4477153 17,11 C17,11.5522847 16.5522847,12 16,12 Z" id="Combined-Shape" fill="currentColor"/>
                    </g>
                </g>
            </g>
        </symbol>
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
    </svg>

@endsection
