<div>
    <title>Edit Terms and Conditions</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/terms-and-conditions">Terms and Conditions</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
            <h2 class="h4">Edit Terms and Conditions</h2>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form wire:submit.prevent="update" onsubmit="saveTinyMCEContent();">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">Terms and Conditions Information</h2>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title">Title</label>
                            <input wire:model="title" class="form-control" id="title" type="text" placeholder="Enter title">
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="content">Content</label>
                            <textarea wire:ignore.self class="form-control" id="content" rows="20" placeholder="Enter Terms and Conditions content">{{ $content }}</textarea>
                            <input type="hidden" wire:model="content" id="content_hidden">
                            @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">
                                <strong>Word-style Editor:</strong> Use the toolbar above to format your text (bold, bullet points, headings, etc.). Content will be automatically saved as HTML.
                            </small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3" style="display: none;">
                            <label for="content_type">Content Type</label>
                            <select wire:model="content_type" class="form-control" id="content_type">
                                <option value="html" selected>HTML</option>
                            </select>
                            @error('content_type') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="pdf_url">PDF URL (Optional)</label>
                            <input wire:model="pdf_url" class="form-control" id="pdf_url" type="url" placeholder="https://example.com/terms.pdf">
                            @error('pdf_url') <span class="text-danger">{{ $message }}</span> @enderror
                            <small class="form-text text-muted">Optional: URL to a PDF version of the Terms and Conditions.</small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch">
                                <input wire:model="is_active" class="form-check-input" type="checkbox" id="is_active">
                                <label class="form-check-label" for="is_active">Set as Active (will deactivate other terms)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="update">
            <span wire:loading.remove wire:target="update">Update Terms and Conditions</span>
            <span wire:loading wire:target="update">Updating...</span>
        </button>
        <a href="/terms-and-conditions" class="btn btn-secondary">Cancel</a>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit for Livewire to set the content
            setTimeout(function() {
                initializeTinyMCE();
            }, 300);
        });

        // Function to save TinyMCE content before form submission
        function saveTinyMCEContent() {
            if (typeof tinymce !== 'undefined') {
                var editor = tinymce.get('content');
                if (editor) {
                    var content = editor.getContent();
                    editor.save();
                    // Force update Livewire with skip parameter
                    @this.set('content', content, true);
                    @this.set('content_type', 'html', true);
                    // Also update the textarea directly as backup
                    var textarea = document.getElementById('content');
                    if (textarea) {
                        textarea.value = content;
                    }
                }
            }
        }

        function initializeTinyMCE() {
            if (typeof tinymce === 'undefined') {
                console.error('TinyMCE not loaded');
                return;
            }

            // Get initial content from the textarea (set by Livewire)
            var textarea = document.getElementById('content');
            var initialContent = textarea ? textarea.value : '';

            // Initialize TinyMCE
            tinymce.init({
                selector: '#content',
                height: 500,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount',
                    'paste' // Add paste plugin for Word content handling
                ],
                toolbar: 'undo redo | formatselect | ' +
                    'bold italic underline | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help',
                content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; }',
                // Configure paste plugin to handle Word content
                paste_as_text: false, // Keep formatting
                paste_auto_cleanup_on_paste: false, // Don't auto-cleanup (let us handle it)
                paste_remove_styles: false, // Keep styles
                paste_remove_styles_if_webkit: false, // Keep webkit styles
                paste_strip_class_attributes: 'none', // Don't strip class attributes
                paste_retain_style_properties: 'color font-size font-family font-weight font-style text-align list-style-type margin padding', // Keep these styles
                paste_merge_formats: true, // Merge formats
                paste_enable_default_filters: true, // Enable default filters
                paste_word_valid_elements: 'b,strong,i,em,h1,h2,h3,h4,h5,h6,p,ol,ul,li,a[href],span,color,font-size,font-weight,font-style,text-decoration,br', // Valid elements from Word
                setup: function(editor) {
                    // Set initial content
                    if (initialContent) {
                        editor.on('init', function() {
                            editor.setContent(initialContent);
                        });
                    }
                    
                    // Handle paste events to ensure content is preserved
                    editor.on('paste', function(e) {
                        // Wait for paste to complete, then save
                        setTimeout(function() {
                            var content = editor.getContent();
                            editor.save();
                            // Update textarea
                            var textarea = document.getElementById('content');
                            if (textarea) {
                                textarea.value = content;
                            }
                            // Update hidden input
                            var hiddenInput = document.getElementById('content_hidden');
                            if (hiddenInput) {
                                hiddenInput.value = content;
                                hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                            // Force update Livewire
                            @this.set('content', content, true);
                            @this.set('content_type', 'html', true);
                        }, 300);
                    });
                    
                    // Handle paste after it's processed
                    editor.on('pastepostprocess', function(e) {
                        setTimeout(function() {
                            var content = editor.getContent();
                            editor.save();
                            // Update textarea
                            var textarea = document.getElementById('content');
                            if (textarea) {
                                textarea.value = content;
                            }
                            // Update hidden input
                            var hiddenInput = document.getElementById('content_hidden');
                            if (hiddenInput) {
                                hiddenInput.value = content;
                                hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                            @this.set('content', content, true);
                            @this.set('content_type', 'html', true);
                        }, 100);
                    });
                    
                    // Sync with Livewire when content changes
                    editor.on('change', function() {
                        var content = editor.getContent();
                        editor.save();
                        // Update textarea and hidden input
                        var textarea = document.getElementById('content');
                        if (textarea) textarea.value = content;
                        var hiddenInput = document.getElementById('content_hidden');
                        if (hiddenInput) {
                            hiddenInput.value = content;
                            hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                        @this.set('content', content, true);
                        @this.set('content_type', 'html', true);
                    });
                    
                    editor.on('blur', function() {
                        var content = editor.getContent();
                        editor.save();
                        // Update textarea and hidden input
                        var textarea = document.getElementById('content');
                        if (textarea) textarea.value = content;
                        var hiddenInput = document.getElementById('content_hidden');
                        if (hiddenInput) {
                            hiddenInput.value = content;
                            hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                        }
                        @this.set('content', content, true);
                        @this.set('content_type', 'html', true);
                    });
                    
                    // Also sync on keyup to catch all changes
                    var keyupTimeout;
                    editor.on('keyup', function() {
                        clearTimeout(keyupTimeout);
                        keyupTimeout = setTimeout(function() {
                            var content = editor.getContent();
                            editor.save();
                            // Update textarea and hidden input
                            var textarea = document.getElementById('content');
                            if (textarea) textarea.value = content;
                            var hiddenInput = document.getElementById('content_hidden');
                            if (hiddenInput) {
                                hiddenInput.value = content;
                                hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                            }
                            @this.set('content', content, true);
                            @this.set('content_type', 'html', true);
                        }, 300);
                    });
                }
            });
        }
    </script>
</div>
