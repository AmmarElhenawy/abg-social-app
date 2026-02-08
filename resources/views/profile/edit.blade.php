@extends('layouts.app')

@section('title', 'تعديل الملف الشخصي')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Update Profile Information -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">معلومات الملف الشخصي</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="name" class="form-label">الاسم</label>
                            <input type="text" name="name" id="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" name="email" id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if(session('status') === 'profile-updated')
                            <div class="alert alert-success">
                                تم تحديث الملف الشخصي بنجاح!
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> حفظ التغييرات
                        </button>
                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">تحديث كلمة المرور</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                            <input type="password" name="current_password" id="current_password" 
                                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required>
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" name="password" id="password" 
                                   class="form-control @error('password', 'updatePassword') is-invalid @enderror" required>
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control" required>
                        </div>

                        @if(session('status') === 'password-updated')
                            <div class="alert alert-success">
                                تم تحديث كلمة المرور بنجاح!
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-key"></i> تحديث كلمة المرور
                        </button>
                    </form>
                </div>
            </div>

            <!-- Delete Account -->
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">حذف الحساب</h5>
                </div>
                <div class="card-body">
                    <p class="text-danger">
                        <strong>تحذير:</strong> بمجرد حذف حسابك، سيتم حذف جميع بياناتك ومنشوراتك بشكل دائم.
                    </p>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="bi bi-trash"></i> حذف الحساب
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">تأكيد حذف الحساب</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>هل أنت متأكد من رغبتك في حذف حسابك؟ هذا الإجراء لا يمكن التراجع عنه.</p>
                    <div class="mb-3">
                        <label for="password" class="form-label">أدخل كلمة المرور للتأكيد</label>
                        <input type="password" name="password" id="password" 
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror" required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-danger">حذف الحساب نهائياً</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection