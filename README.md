# Drak'thul Jelita

I made this so i can upload screenshots of cool names from WoW that I collected over the years.
Other people can look at them I guess.

Some technical details:

- Backend is made in Laravel
- Frontend is made with Blade templates, Tailwind, daisyUI and a little bit of TypeScript
  - I made this mainly so I could try out fullstack Laravel and also daisyUI
- Only admin can upload, edit and delete screenshots
- There is tesseract.js OCR in the upload form page, so you don't have to type out the names
  manually
- Screenshots are stored in S3
