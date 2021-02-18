<ul>
    @foreach($childs as $child)
        <li>
	    <label class="post_users" for="{{$child->id}}"> {{$child->title}}</label>
            <input name="branch_code" value="{{$child->id}}" type="checkbox" style="display: none" />
            <ul>
                <li>
                    <div class="form-group" id="usersdiv{{$child->id}}">

                    </div>
                </li>
            </ul>
            @if(count($child->childs))
                @include('messages.ajax',['childs' => $child->childs])
            @endif
        </li>
    @endforeach
</ul>