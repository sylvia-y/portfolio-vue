import fs from 'node:fs'
import path from 'node:path'
import vue from '@vitejs/plugin-vue'
import { defineConfig } from 'vite'

function publicIndexHtml() {
  const sendIndex = (publicDir, url, res, next) => {
    const pathname = decodeURIComponent((url || '').split('?')[0])
    if (
      pathname.startsWith('/@') ||
      pathname.startsWith('/src/') ||
      pathname.startsWith('/node_modules/') ||
      path.extname(pathname)
    ) {
      return next()
    }

    const rel = pathname.replace(/^\/+|\/+$/g, '')
    if (!rel) return next()

    const file = path.join(publicDir, rel, 'index.html')
    if (!fs.existsSync(file)) return next()

    res.setHeader('Content-Type', 'text/html; charset=utf-8')
    res.end(fs.readFileSync(file))
  }

  return {
    name: 'public-index-html',
    configureServer(server) {
      const publicDir = path.resolve(server.config.root, 'public')
      server.middlewares.use((req, res, next) => {
        sendIndex(publicDir, req.originalUrl || req.url, res, next)
      })
    },
    configurePreviewServer(server) {
      const publicDir = path.resolve(server.config.root, 'dist')
      server.middlewares.use((req, res, next) => {
        sendIndex(publicDir, req.originalUrl || req.url, res, next)
      })
    },
  }
}

export default defineConfig({
  plugins: [vue(), publicIndexHtml()],
})
