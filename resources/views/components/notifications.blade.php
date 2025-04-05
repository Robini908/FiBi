<div>
    {{-- tall-toasts container --}}
    <div
        x-data
        @toast.window="$toast($event.detail)"
    ></div>
</div> 