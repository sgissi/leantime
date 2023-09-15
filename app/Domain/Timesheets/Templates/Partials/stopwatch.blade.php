@if ($login::userIsAtLeast(\Leantime\Domain\Auth\Models\Roles::$editor, true))

    <li class='timerHeadMenu' id='timerHeadMenu' hx-get="{{BASE_URL}}/timesheets/stopwatch/get-status" hx-trigger="timerUpdate from:body">

    @if ($onTheClock !== false|null)



            <a
                href='javascript:void(0);'
                class='dropdown-toggle'
                data-toggle='dropdown'

            >{!! sprintf(
                    __('text.timer_on_todo'),
                    $onTheClock['totalTime'],
                    substr($onTheClock['headline'], 0, 10)
                ) !!}</a>

            <ul class="dropdown-menu">
                <li>
                    <a href="#/tickets/showTicket/{{ $onTheClock['id'] }}">
                        {!! __('links.view_todo') !!}
                    </a>
                </li>
                <li>
                    <a
                        href="javascript:void(0);"
                        class="punchOut"
                        data-value="{{ $onTheClock['id'] }}"
                        hx-target="#timerHeadMenu"
                        hx-swap="outerHTML"
                        hx-vals='{"myVal": "{{ $onTheClock["id"] }}", "action":"stop"}'
                    >{!! __('links.stop_timer') !!}</a>
                </li>
            </ul>

    @endif

    </li>

@endif

