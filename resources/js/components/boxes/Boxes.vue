<template>
	<div class="page">
		<div class="page-header">
			<h3>
				Cajas
			</h3>
		</div>
		<div class="page-content">
			<main-box />
			<box />
		</div>
	</div>
</template>

<script>
import Box from "./Box.vue";
import MainBox from "./MainBox.vue";
export default {
	components: { MainBox, Box },
};
</script>
