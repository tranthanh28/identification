<template>
<IdentificationComponent :title="title" :url-api="urlApi"></IdentificationComponent>
</template>

<script>
import IdentificationComponent from "@/app/Monitaz/Components/IdentificationComponent";
export default {
  components: {IdentificationComponent},
  data() {
    return {
      title: "Identification",
      urlApi: "/monitaz/identification"
    }
  }
}
</script>
