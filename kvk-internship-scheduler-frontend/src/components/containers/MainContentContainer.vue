<template>
    <div class="main-content-container">
        <slot></slot>
    </div>
</template>

<style scoped>
.main-content-container {
    padding: 0px 200px;
}
@media (max-width:1100px){
    .main-content-container {
        padding: 0px 20px;
    }
}
</style>