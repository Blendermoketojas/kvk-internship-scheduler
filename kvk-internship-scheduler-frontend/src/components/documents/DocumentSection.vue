<template>
    <div class="document-section-container">
        <span class="m-2 fs-5 font-bold">Svarbus dokumentai</span>
        <slot></slot>
    </div>
</template>

<script>
export default {
    props: {
        sectionName: {
            required: true,
            type: String,
            default: 'Svarbus dokumentai'
        }
    }
}
</script>

<style>
.document-section-container {
    border: 2px solid black;
    border-radius: 5px;
    margin: 7px;
}
</style>