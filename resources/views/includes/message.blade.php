@if (session('message'))
<div class="alert alert-primary alert-dismissible fade show mb-3" role="alert">
    {{ session('message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('secondary_message'))
<div class="alert alert-secondary alert-dismissible fade show mb-3" role="alert">
    {{ session('secondary_message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('success_message'))
<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
    {{ session('success_message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('error_message'))
<div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
    {{ session('error_message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('warning_message'))
<div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
    {{ session('warning_message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('info_message'))
<div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
    {{ session('info_message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('light_message'))
<div class="alert alert-light alert-dismissible fade show mb-3" role="alert">
    {{ session('light_message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
@if (session('dark_message'))
<div class="alert alert-dark alert-dismissible fade show mb-3" role="alert">
    {{ session('dark_message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
