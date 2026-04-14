@extends('layouts.app')

@section('title', 'Contact Us - BookQueue')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Contact Info -->
        <div class="col-md-5 mb-4">
            <h2 class="mb-3">Contact Us</h2>
            <p class="text-muted">
                Have questions about books, orders, or recommendations? We'd love to hear from you!
            </p>

            <div class="mt-4">
                <p><strong>Email:</strong> support@bookqueue.store</p>
                <!-- <p><strong>Phone:</strong> +44 98765 43210</p> -->
                <p><strong>Address:</strong> Landon</p>
            </div>

            <div class="mt-4">
                <h5>Follow Us</h5>
                <a href="#" class="me-2">Facebook</a>
                <a href="#" class="me-2">Instagram</a>
                <a href="#">Twitter</a>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="col-md-7">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">Send us a message</h4>

                    {{-- Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('contact/submit') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control"
                                   value="{{ old('subject') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea name="message" rows="5" class="form-control" required>{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
