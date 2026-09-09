from fastapi import FastAPI, UploadFile, File
from fastapi.middleware.cors import CORSMiddleware
import pandas as pd
import io

app = FastAPI(
    title="Sales Analyzer API",
    description="CSV sales data analysis API",
    version="1.0.0",
)

# 허용할 출처(Origin) 목록
origins = [
    "https://portfolio-vue-tan.vercel.app",  # Vercel 배포 주소
    "http://localhost:5173",                 # Vue Vite 로컬 개발 주소
    "http://localhost:3000",
    "http://127.0.0.1:5173",
]

app.add_middleware(
    CORSMiddleware,
    allow_origins=origins,
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/")
def root():
    return {
        "message": "Sales Analyzer API is running"
    }


@app.post("/analyze")
async def analyze_sales(file: UploadFile = File(...)):
    contents = await file.read()

    df = pd.read_csv(io.BytesIO(contents))

    # 매출 계산
    df["sales"] = df["quantity"] * df["price"]

    # 전체 매출
    total_sales = int(df["sales"].sum())

    # 주문 건수
    order_count = len(df)

    # 총 판매 수량
    total_quantity = int(df["quantity"].sum())

    # 평균 주문 금액
    average_order = round(total_sales / order_count)

    # 날짜별 매출
    daily_sales = (
        df.groupby("date")["sales"]
        .sum()
        .reset_index()
        .to_dict(orient="records")
    )

    # 상품별 매출
    product_sales = (
        df.groupby("product")["sales"]
        .sum()
        .sort_values(ascending=False)
        .reset_index()
        .to_dict(orient="records")
    )

    return {
        "summary": {
            "total_sales": total_sales,
            "order_count": order_count,
            "total_quantity": total_quantity,
            "average_order": average_order,
        },
        "daily_sales": daily_sales,
        "product_sales": product_sales,
        "status": "success", "message": "CORS 설정 완료"
    }