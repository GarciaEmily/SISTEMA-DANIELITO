<div class="panel">

    <details>

        <summary class="summary-vulnerables">
            🎂 Cumpleaños del mes
            ({{ $cumpleanosCercanos->count() }})
        </summary>

        @if($cumpleanosCercanos->count())

            <table>

                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Grupo</th>
                        <th>Cumpleaños</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($cumpleanosCercanos as $nino)

                    <tr>

                        <td>{{ $nino->codigo }}</td>

                        <td>
                            {{ $nino->nombres }}
                            {{ $nino->apellidos }}
                        </td>

                        <td>
                            {{ $nino->grupo->nombre ?? 'Sin grupo' }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($nino->fecha_nacimiento)->format('d/m') }}
                        </td>

                        <td>
                            <a href="{{ route('ninos.show',$nino->id) }}"
                               class="btn btn-naranja">
                                👁 Ver ficha
                            </a>
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

        @else

            <div class="success-box">
                🎉 No hay cumpleaños este mes.
            </div>

        @endif

    </details>

</div>
