<script>
    import Quill from 'quill';
    /* import 'quill/dist/quill.core.css'
    import 'quill/dist/quill.bubble.css' */
    export default {
        props: {
            value: {
                type: String,
                default: ''
            }
        },

        data() {
            return {
                editor: null
            };
        },
        mounted() {
            this.editor = new Quill(this.$refs.editor, {
                modules: {
                    toolbar: [
                        [{ header: [1, 2, 3, 4, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link']
                    ]
                },
                theme: 'snow',
                formats: ['bold', 'underline', 'header', 'italic', 'list', 'link']
            });

            this.editor.root.innerHTML = this.value;

            this.editor.on('text-change', () => this.update());
        },

        methods: {
            update() {
                this.$emit('input', this.editor.getText() ? this.editor.root.innerHTML : '');
            }
        }
    }
</script>

<template>
    <div ref="editor" style="border: 1px solid black; min-height: 300px"></div>
</template>