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
                            <textarea wire:ignore class="form-control" id="content" rows="20" placeholder="Enter Terms and Conditions content">{{ $content }}</textarea>
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
                    editor.save();
                    @this.set('content', editor.getContent());
                    @this.set('content_type', 'html');
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
                paste_auto_cleanup_on_paste: true, // Clean up Word formatting
                paste_remove_styles: false, // Keep styles
                paste_remove_styles_if_webkit: true, // Remove webkit styles
                paste_strip_class_attributes: 'all', // Remove class attributes
                paste_retain_style_properties: 'color font-size font-family font-weight font-style text-align list-style-type', // Keep these styles
                paste_merge_formats: true, // Merge formats
                setup: function(editor) {
                    // Set initial content
                    if (initialContent) {
                        editor.on('init', function() {
                            editor.setContent(initialContent);
                        });
                    }
                    
                    // Handle paste events to ensure content is preserved
                    editor.on('paste', function(e) {
                        // Let TinyMCE handle the paste, but ensure content is saved after paste completes
                        setTimeout(function() {
                            editor.save();
                            @this.set('content', editor.getContent());
                            @this.set('content_type', 'html');
                        }, 200);
                    });
                    
                    // Also handle paste preprocess to ensure content is captured
                    editor.on('pastepreprocess', function(e) {
                        // Content will be processed by TinyMCE
                    });
                    
                    // Sync with Livewire when content changes
                    editor.on('change', function() {
                        editor.save();
                        @this.set('content', editor.getContent());
                        @this.set('content_type', 'html');
                    });
                    editor.on('blur', function() {
                        editor.save();
                        @this.set('content', editor.getContent());
                        @this.set('content_type', 'html');
                    });
                    
                    // Also sync on keyup to catch all changes
                    editor.on('keyup', function() {
                        editor.save();
                        @this.set('content', editor.getContent());
                        @this.set('content_type', 'html');
                    });
                }
            });
        }
    </script>
</div>
