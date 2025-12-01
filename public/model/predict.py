import sys
import warnings
import os
import joblib
import numpy as np

# Suppress semua warnings dengan lebih agresif
warnings.filterwarnings('ignore')
os.environ['PYTHONWARNINGS'] = 'ignore'

# Suppress sklearn warnings secara spesifik
import logging
logging.getLogger('sklearn').setLevel(logging.ERROR)

# Ambil input dari argumen
weight = float(sys.argv[1])
umur = float(sys.argv[2])

# Dapatkan direktori script ini (agar model bisa ditemukan)
script_dir = os.path.dirname(os.path.abspath(__file__))
model_path = os.path.join(script_dir, "model_pakan_2fitur.joblib")

# Pastikan model file ada
if not os.path.exists(model_path):
    print(f"ERROR: Model file tidak ditemukan di: {model_path}", file=sys.stderr)
    sys.exit(1)

# Load model
model = joblib.load(model_path)

# Prediksi
X = np.array([[weight, umur]])
bk = model.predict(X)[0]

# Print hasil prediksi (hanya angka, tanpa spasi atau karakter lain)
print(bk, flush=True)
