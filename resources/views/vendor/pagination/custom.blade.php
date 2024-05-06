<div class="row">
    <div class="col-12">
        <!-- <div class="pagination"> -->
            <nav aria-label="..."  style="float: right;">
                @if ($paginator->hasPages())
                <ul class="pagination">

                    @if ($paginator->onFirstPage())
                    <li class="page-item disabled"><a class="page-link" href="javascript:;" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    @else
                    
                    <li class="page-item"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    @endif



                    @foreach ($elements as $element)

                    @if (is_string($element))

                    <li class="page-item disabled"><a class="page-link" href="javascript:;">{{ $element }}</a>
                    </li>
                    @endif



                    @if (is_array($element))
                    @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                    <li class="page-item active" aria-current="page"><a class="page-link" href="javascript:;">{{ $page }} <span class="visually-hidden">{{ $page }}</span></a>
                    </li>
                    @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                    @endforeach
                    @endif
                    @endforeach



                    @if ($paginator->hasMorePages())
                   
                    <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">Next</a>
                                    </li>
                    @else
                    <li class="page-item disabled"><a class="page-link" href="javascript:void(0)">Next</a>
                                    </li>
                    @endif
                </ul>
                @endif 
            </nav>
            <!-- </div> -->
        </div>
    </div>