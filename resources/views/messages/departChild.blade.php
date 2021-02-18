<ul>

    @foreach($childs as $child)

        <li>

            {{ $child->title }}<br/>
                <ul>
                        <li>
                                @foreach($to_users as $user)
                                        @if($child->id == $user->depart_id)

                                                <div class="form-check  .form-group{{ $errors->has('to_users') ? 'has-error' : '' }}">
                                                        <input  id="to_users" type="checkbox" name="to_users[]" value="{{ $user->id }}" class="flat-red">
                                                        <label class="form-check-label" style="color: initial" for="materialUnchecked">{{ $user->fname.' '.$user->lname }} <span style="font-size: x-small;font-style: italic;color: #31708f;">{{$user->job_title}}</span></label>
                                                    @if ($errors->has('to_users'))
                                                        <span class="text-red" role="alert">
                                                            <strong>{{ $errors->first('to_users') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>
                                        @endif
                                @endforeach

                        </li>
                </ul>

            @if(count($child->childs))

                @include('messages.departChild',['childs' => $child->childs])

            @endif

        </li>

    @endforeach

</ul>