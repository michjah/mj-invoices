<template>
    <div>
        <h2>Neexistující stránka</h2>
        <p>Je mi líto, ale stránka neexistuje</p>
        <br/>
        <router-link :to="{name: 'Home'}">Back to Home Page</router-link>
    </div>
</template>

<script>
export default {
    name: 'NotFound'
}
</script>
