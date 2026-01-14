<div>
    <title>Create Terms and Conditions</title>
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/terms-and-conditions">Terms and Conditions</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
            <h2 class="h4">Create Terms and Conditions</h2>
        </div>
    </div>

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form wire:submit.prevent="save" onsubmit="saveTinyMCEContent();">
        <div class="row">
            <div class="col-12 col-xl-12">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">Terms and Conditions Information</h2>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="title">Title</label>
                            <input wire:model="title" class="form-control" id="title" type="text" placeholder="Enter title (e.g., Terms and Conditions)">
                            @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="content">Content</label>
                            <textarea wire:ignore.self class="form-control" id="content" rows="20" placeholder="Enter Terms and Conditions content"></textarea>
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
        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
            <span wire:loading.remove wire:target="save">Create Terms and Conditions</span>
            <span wire:loading wire:target="save">Creating...</span>
        </button>
        <a href="/terms-and-conditions" class="btn btn-secondary">Cancel</a>
    </form>

    <script>
        // Wait for TinyMCE to load
        function waitForTinyMCE(callback) {
            if (typeof tinymce !== 'undefined' && tinymce.init) {
                callback();
            } else {
                setTimeout(function() {
                    waitForTinyMCE(callback);
                }, 100);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            waitForTinyMCE(function() {
                initializeTinyMCE();
            });
        });

        // Prevent Livewire from clearing the editor on updates
        document.addEventListener('livewire:load', function() {
            waitForTinyMCE(function() {
                if (!tinymce.get('content')) {
                    initializeTinyMCE();
                }
            });
        });

        // Function to save TinyMCE content before form submission
        function saveTinyMCEContent() {
            if (typeof tinymce !== 'undefined') {
                var editor = tinymce.get('content');
                if (editor) {
                    var content = editor.getContent();
                    editor.save();
                    // Update hidden input
                    var hiddenInput = document.getElementById('content_hidden');
                    if (hiddenInput) {
                        hiddenInput.value = content;
                    }
                    // Force update Livewire
                    @this.set('content', content, true);
                    @this.set('content_type', 'html', true);
                }
            }
        }

        function initializeTinyMCE() {
            // Check if editor already exists
            if (tinymce.get('content')) {
                return; // Editor already initialized
            }

            // Initialize TinyMCE
            tinymce.init({
                selector: '#content',
                height: 500,
                menubar: false,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount',
                    'paste'
                ],
                toolbar: 'undo redo | formatselect | ' +
                    'bold italic underline | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help',
                content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; line-height: 1.6; }',
                // Configure paste plugin - simplified to preserve content
                paste_as_text: false,
                paste_auto_cleanup_on_paste: false,
                paste_remove_styles: false,
                paste_remove_styles_if_webkit: false,
                paste_strip_class_attributes: 'none',
                paste_retain_style_properties: 'all',
                paste_merge_formats: true,
                setup: function(editor) {
                    // Sync content to Livewire after paste completes
                    editor.on('paste', function(e) {
                        // Use longer timeout to ensure paste is fully processed
                        setTimeout(function() {
                            syncContent(editor);
                        }, 500);
                    });
                    
                    // Also sync on any content change
                    editor.on('change', function() {
                        syncContent(editor);
                    });
                    
                    editor.on('blur', function() {
                        syncContent(editor);
                    });
                    
                    // Sync on keyup (debounced)
                    var keyupTimeout;
                    editor.on('keyup', function() {
                        clearTimeout(keyupTimeout);
                        keyupTimeout = setTimeout(function() {
                            syncContent(editor);
                        }, 500);
                    });
                }
            });
        }

        function syncContent(editor) {
            try {
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
                    // Trigger Livewire update
                    hiddenInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
                
                // Update Livewire
                @this.set('content', content, true);
                @this.set('content_type', 'html', true);
            } catch (e) {
                console.error('Error syncing content:', e);
            }
        }
    </script>
</div>
