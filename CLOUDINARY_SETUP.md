# Cloudinary Setup Guide

## ✅ Configuration Status: COMPLETED

Your Cloudinary account has been configured successfully!

### Current Configuration

-   **Cloud Name:** `drfdmdgoa`
-   **API Key:** `466968418586524`
-   **Status:** ✅ Active and ready to use

### Configured in `.env`:

```env
CLOUDINARY_URL=cloudinary://466968418586524:Nu0KlXb8n9fEQ-SFhK0AgI1ftNE@drfdmdgoa
CLOUDINARY_CLOUD_NAME=drfdmdgoa
CLOUDINARY_API_KEY=466968418586524
CLOUDINARY_API_SECRET=Nu0KlXb8n9fEQ-SFhK0AgI1ftNE
```

### Next Steps

You can now:

1. Go to Orders page
2. Click "Add Payment" on any order
3. Upload payment proof images
4. Images will be automatically stored in Cloudinary

**Dashboard URL:** https://console.cloudinary.com/console/c-466968418586524/media_library/folders/home

## Features Implemented

### Payment Modal

-   Upload multiple payment proof images
-   Images are stored in Cloudinary under `payments/` folder
-   Automatic image deletion when payment is deleted
-   Supports JPG, PNG formats (max 10MB each)

### Image Storage Structure

```
cloudinary.com/your-cloud-name/
└── payments/
    ├── payment_proof_1.jpg
    ├── payment_proof_2.jpg
    └── ...
```

## Testing

To test the payment upload:

1. Go to Orders page
2. Click 3-dot menu on any order
3. Select "Add Payment"
4. Fill in payment details
5. Upload payment proof images
6. Submit

Images will be uploaded to Cloudinary and URLs stored in database.

## Troubleshooting

### Error: "Undefined type 'CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary'"

-   Run: `composer dump-autoload`
-   Run: `php artisan config:clear`

### Upload fails

-   Check your Cloudinary credentials in `.env`
-   Verify internet connection
-   Check file size (max 10MB per image)
-   Verify file format (JPG, PNG only)

### Images not displaying

-   Check if `img_url` column contains valid JSON array
-   Verify Cloudinary URLs are accessible
