import { onMounted, onUnmounted, ref } from 'vue'

export function useScrollAnimation(options = {}) {
  const elements = ref([])
  const threshold = options.threshold || 0.1
  const rootMargin = options.rootMargin || '0px 0px -50px 0px'

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('visible')
          // Optional: stop observing after animation
          if (options.once !== false) {
            observer.unobserve(entry.target)
          }
        } else if (options.once === false) {
          entry.target.classList.remove('visible')
        }
      })
    },
    {
      threshold,
      rootMargin,
    }
  )

  const registerElement = (el) => {
    if (el && !elements.value.includes(el)) {
      elements.value.push(el)
      observer.observe(el)
    }
  }

  onUnmounted(() => {
    elements.value.forEach((el) => {
      observer.unobserve(el)
    })
    observer.disconnect()
  })

  return { registerElement }
}

