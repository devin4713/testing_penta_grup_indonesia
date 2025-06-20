def fibo(n):
    if n <= 0:
        return None
    a, b = 1, 1 # anggap 2 angka pertama 1, 1
    for _ in range(3, n + 1):
        a, b = b, a + b
    return a if n == 1 else b

try:
    nval = int(input("Masukkan nilai n >> "))
    hasil = fibo(nval)
    if hasil is not None:
        print(f"Fibonacci ke {nval} adalah {hasil}")
    else:
        print("nilai n harus lebih dari 0")
except:
    print("Harap masukkan angka bulat yang valid.")