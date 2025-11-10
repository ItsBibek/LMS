<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fine Warning - Library Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }
        .content {
            background: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-radius: 0 0 8px 8px;
        }
        .warning-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .book-details {
            background: white;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .book-details h3 {
            margin-top: 0;
            color: #667eea;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #6b7280;
        }
        .value {
            color: #111827;
        }
        .fine-info {
            background: #fee2e2;
            border: 1px solid #fecaca;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
        }
        .fine-amount {
            font-size: 24px;
            font-weight: bold;
            color: #dc2626;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0;">📚 Library Fine Warning</h1>
    </div>
    
    <div class="content">
        <p>Dear <strong>{{ $student->student_name }}</strong>,</p>
        
        <div class="warning-box">
            <strong>⚠️ Important Notice:</strong> Your borrowed book is due <strong>tomorrow</strong>!
        </div>

        <p>This is a friendly reminder that one of your borrowed books is due for return tomorrow. To avoid fines, please return the book by the due date.</p>

        <div class="book-details">
            <h3>📖 Book Details</h3>
            <div class="detail-row">
                <span class="label">Book Title:</span>
                <span class="value">{{ $book->Title ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Author:</span>
                <span class="value">{{ $book->Author ?? 'N/A' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Accession Number:</span>
                <span class="value">{{ $issue->Accession_Number }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Issue Date:</span>
                <span class="value">{{ \Carbon\Carbon::parse($issue->issue_date)->format('d M Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Due Date:</span>
                <span class="value"><strong>{{ \Carbon\Carbon::parse($issue->due_date)->format('d M Y') }}</strong></span>
            </div>
        </div>

        <div class="fine-info">
            <p style="margin: 0 0 10px 0;">Starting from the day after the due date:</p>
            <div class="fine-amount">Rs. {{ $finePerDay }} per day</div>
            <p style="margin: 10px 0 0 0; font-size: 14px;">will be added to your fine.</p>
        </div>

        <p><strong>What to do next:</strong></p>
        <ul>
            <li>Return the book to the library before the due date to avoid fines</li>
            <li>If you need more time, contact the library to request an extension</li>
            <li>Check your account for any other pending returns</li>
        </ul>

   

        <div class="footer">
            <p><strong>Library Management System</strong></p>
            <p>This is an automated notification. Please do not reply to this email.</p>
            <p style="font-size: 12px; margin-top: 10px;">
                If you have already returned this book, please disregard this message.
            </p>
        </div>
    </div>
</body>
</html>
