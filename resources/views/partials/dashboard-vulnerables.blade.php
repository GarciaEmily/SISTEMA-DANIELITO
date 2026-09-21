<div class="panel">

    <details>

        <summary class="summary-vulnerables">
            ⚠️ Niños en condición vulnerable
            ({{ $listaVulnerables->count() }})
        </summary>

        @if($listaVulnerables->count())

            <table>

                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Grupo</th>
                        <th>Acción</th>
                    </tr>
                </thead>

                <tbody>

                @foreach($listaVulnerables as $nino)

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
                ✅ No existen niños vulnerables registrados.
            </div>

        @endif

    </details>

</div>
