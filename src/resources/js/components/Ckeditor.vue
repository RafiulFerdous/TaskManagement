<template>
  <div>
    <ckeditor
      :editor="editor"
      v-model="editorData"
      :config="editorConfig"
    ></ckeditor>
  </div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import { ref } from 'vue'

export default {
  name: 'CKEditorComponent',
  setup() {
    const editorData = ref('')
    const editor = ref(ClassicEditor)
    const editorConfig = ref({
      toolbar: {
        items: [
          'bold',
          'italic',
          '|',
          'imageUpload', // Add image upload button
          '|',
          'undo',
          'redo'
        ]
      },
      image: {
        toolbar: ['imageTextAlternative', '|', 'imageStyle:full', 'imageStyle:side']
      }
    })

    // Function to handle image upload
    function handleImageUpload(file) {
      return new Promise((resolve, reject) => {
        const formData = new FormData()
        formData.append('image', file)

        // Make your API call here to upload the image
        // Replace 'your-upload-api-url' with your actual API endpoint
        fetch('your-upload-api-url', {
          method: 'POST',
          body: formData
        })
          .then(response => response.json())
          .then(result => {
            // Assuming your API returns the URL of the uploaded image
            const imageUrl = result.imageUrl

            // Resolve the promise with the uploaded image URL
            resolve({ default: imageUrl })
          })
          .catch(error => {
            console.error('Error uploading image:', error)
            reject(error)
          })
      })
    }

    return {
      editorData,
      editor,
      editorConfig,
      handleImageUpload
    }
  }
}
</script>
