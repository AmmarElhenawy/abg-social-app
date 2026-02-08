@extends('layouts.app')

@section('title', 'تعديل المنشور')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">تعديل المنشور</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">المحتوى</label>
                            <textarea name="content" id="content" 
                                      class="form-control @error('content') is-invalid @enderror" 
                                      rows="6" required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($post->image)
                            <div class="mb-3">
                                <label class="form-label">الصورة الحالية</label>
                                <div>
                                    <img src="{{ asset('storage/' . $post->image) }}" class="img-thumbnail" style="max-width: 300px;">
                                </div>
                                <div class="form-check mt-2">
                                    <input type="checkbox" name="remove_image" value="1" class="form-check-input" id="removeImage">
                                    <label class="form-check-label" for="removeImage">
                                        حذف الصورة
                                    </label>
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="image" class="form-label">{{ $post->image ? 'تغيير الصورة' : 'إضافة صورة' }}</label>
                            <input type="file" name="image" id="image" 
                                   class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('posts.show', $post) }}" class="btn btn-secondary">إلغاء</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection